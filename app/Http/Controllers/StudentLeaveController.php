<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\ClassSchedule;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\StudentLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentLeaveController extends Controller
{
    // POST /students/{student}/leaves
    public function store(Request $request, Student $student)
    {
        $data = $request->validate([
            'enrollment_id'      => ['required', 'exists:enrollments,id'],
            'class_schedule_id'  => ['nullable', 'exists:class_schedules,id'],
            'leave_type'         => ['required', 'in:emergency,normal,no_makeup'],
            'leave_date'         => ['required', 'date'],
            'reason'             => ['nullable', 'string', 'max:500'],
        ]);

        $enrollment = Enrollment::with('course')->findOrFail($data['enrollment_id']);
        abort_if($enrollment->student_id !== $student->id, 404);

        // ===== Validation rule: ตรวจสอบสิทธิ์การลาตามเงื่อนไขคอร์ส =====
        // ลาปกติ (ขอเรียนชดเชย) ได้เฉพาะคอร์สที่เปิดสิทธิ์เรียนชดเชยไว้เท่านั้น
        if ($data['leave_type'] === 'normal' && !$enrollment->course->allow_makeup_class) {
            return back()->with('error', 'คอร์สนี้ไม่เปิดสิทธิ์เรียนชดเชย กรุณาเลือก "ลาแบบไม่ชดเชย" แทน');
        }

        // ===== Validation rule: จำนวนครั้งลาฉุกเฉิน 1 ครั้งต่อคอร์ส =====
        if ($data['leave_type'] === 'emergency' && $enrollment->emergencyLeaveRemaining() <= 0) {
            return back()->with('error', "ใช้สิทธิ์ลาฉุกเฉินครบตามโควตาแล้ว ({$enrollment->emergencyLeaveQuota()} ครั้ง/คอร์ส ตามนโยบาย)");
        }

        // ===== Validation rule: ตรวจสอบระยะเวลาการแจ้งลา (ยกเว้นลาฉุกเฉิน) =====
        if ($data['leave_type'] !== 'emergency') {
            $classSchedule = !empty($data['class_schedule_id']) ? ClassSchedule::find($data['class_schedule_id']) : null;

            $targetDateTime = $classSchedule
                ? Carbon::parse($classSchedule->schedule_date->format('Y-m-d') . ' ' . $classSchedule->start_time)
                : Carbon::parse($data['leave_date'])->startOfDay();

            $requiredHours = config('leave.normal_advance_notice_hours', 24);
            $hoursUntil = now()->diffInHours($targetDateTime);

            if ($targetDateTime->isPast() || $hoursUntil < $requiredHours) {
                return back()->with('error', "การลาประเภทนี้ต้องแจ้งล่วงหน้าอย่างน้อย {$requiredHours} ชั่วโมงก่อนถึงวันเรียน (แจ้งกะทันหันได้เฉพาะ \"ลาฉุกเฉิน\" เท่านั้น)");
            }
        }

        $data['student_id'] = $student->id;
        $data['status'] = 'pending';
        $data['is_makeup_required'] = $data['leave_type'] === 'normal';
        $data['makeup_status'] = $data['leave_type'] === 'normal' ? 'pending' : 'not_required';

        $leave = $student->leaves()->create($data);

        // ===== แจ้งเตือนผู้เกี่ยวข้อง =====
        $leaveTypeLabel = $leave->leaveTypeLabel();
        AppNotification::notifyAdmins(
            'คำขอลาเรียนใหม่',
            "{$student->full_name} ขอ{$leaveTypeLabel} วันที่ " . $leave->leave_date->format('d/m/Y') . " (คอร์ส: {$enrollment->course->name})",
            route('students.show', $student) . '#leaves'
        );
        if ($enrollment->teacher_id) {
            AppNotification::notifyTeacher(
                $enrollment->teacher_id,
                'นักเรียนแจ้งลาเรียน',
                "{$student->full_name} ขอ{$leaveTypeLabel} วันที่ " . $leave->leave_date->format('d/m/Y'),
                route('students.show', $student) . '#leaves'
            );
        }

        return back()->with('success', 'ส่งคำขอลาเรียบร้อยแล้ว รอการอนุมัติ');
    }

    // POST /students/{student}/leaves/{leave}/approve
    public function approve(Request $request, Student $student, StudentLeave $leave)
    {
        abort_if($leave->student_id !== $student->id, 404);
        if ($leave->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกดำเนินการไปแล้ว');
        }

        $leave->update(['status' => 'approved', 'reviewed_by' => auth()->user()->name ?? 'แอดมิน', 'reviewed_at' => now()]);

        // ถ้าผูกกับตารางเรียนที่นัดไว้ ให้ยกเลิกคาบนั้นเพราะลาไปแล้ว
        if ($leave->class_schedule_id) {
            $leave->classSchedule?->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'อนุมัติคำขอลาเรียบร้อยแล้ว');
    }

    // POST /students/{student}/leaves/{leave}/reject
    public function reject(Request $request, Student $student, StudentLeave $leave)
    {
        abort_if($leave->student_id !== $student->id, 404);
        if ($leave->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกดำเนินการไปแล้ว');
        }

        $leave->update(['status' => 'rejected', 'reviewed_by' => auth()->user()->name ?? 'แอดมิน', 'reviewed_at' => now()]);

        return back()->with('success', 'ปฏิเสธคำขอลาแล้ว');
    }

    // PATCH /students/{student}/leaves/{leave}/makeup (เดิม)
    public function updateMakeup(Request $request, Student $student, StudentLeave $leave)
    {
        abort_if($leave->student_id !== $student->id, 404);

        $data = $request->validate([
            'makeup_date'   => ['nullable', 'date'],
            'makeup_status' => ['required', 'in:pending,scheduled,completed,not_required'],
        ]);

        $leave->update($data);

        return back()->with('success', 'อัปเดตสถานะเรียนชดเชยเรียบร้อยแล้ว');
    }
}
