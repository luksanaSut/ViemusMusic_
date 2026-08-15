<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\ClassSchedule;
use App\Models\Enrollment;
use App\Models\MakeupRequest;
use App\Models\Student;
use App\Models\StudentLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentLeaveController extends Controller
{
    // POST /students/{student}/leaves — Admin, ตัวนักเรียนเอง, หรือผู้ปกครองของนักเรียนคนนั้นแจ้งลาได้
    public function store(Request $request, Student $student)
    {
        $this->authorizeSelfOrGuardianOrAdmin($request, $student);

        $rules = [
            'enrollment_id'     => ['required', 'exists:enrollments,id'],
            'class_schedule_id' => ['nullable', 'exists:class_schedules,id'],
            'leave_type'        => ['required', 'in:emergency,normal,no_makeup'],
            'leave_date'        => ['required', 'date'],
            'reason'            => ['nullable', 'string', 'max:500'],
        ];

        // ===== Validation rule: ตรวจตารางซ้ำของนักเรียน/อาจารย์/ห้อง + ตรวจ Availability ของอาจารย์ สำหรับวันเรียนชดเชย =====
        if ($data['leave_type'] === 'normal') {
            $makeupTeacher = \App\Models\Teacher::find($data['makeup_teacher_id']);
            $dayOfWeek = Carbon::parse($data['makeup_date'])->dayOfWeek; // 0=อาทิตย์..6=เสาร์ ตรงกับ TeacherAvailability

            if ($makeupTeacher && !$makeupTeacher->isAvailableAt($dayOfWeek, $data['makeup_start_time'], $data['makeup_end_time'])) {
                return back()->withInput()->with('error', 'อาจารย์ที่เลือกไม่ได้ตั้งเวลาว่างไว้ในช่วงนี้ กรุณาเลือกวันเวลาใหม่ตามตาราง Availability ของอาจารย์');
            }

            $conflicts = ClassSchedule::findConflicts(
                $data['makeup_date'],
                $data['makeup_start_time'],
                $data['makeup_end_time'],
                $student->id,
                $data['makeup_teacher_id'],
                $data['makeup_room_id'] ?? null
            );
            if (!empty($conflicts)) {
                return back()->withInput()->with('error', 'ไม่สามารถจองเวลาเรียนชดเชยได้: ' . implode(' / ', $conflicts));
            }
        }

        $data = $request->validate($rules);

        $enrollment = Enrollment::with('course')->findOrFail($data['enrollment_id']);
        abort_if($enrollment->student_id !== $student->id, 404);

        // ===== Validation rule: ตรวจสอบสิทธิ์การลาตามเงื่อนไขคอร์ส =====
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

        // ===== Validation rule: ตรวจตารางซ้ำของนักเรียน/อาจารย์/ห้อง สำหรับวันเรียนชดเชย =====
        if ($data['leave_type'] === 'normal') {
            $conflicts = ClassSchedule::findConflicts(
                $data['makeup_date'],
                $data['makeup_start_time'],
                $data['makeup_end_time'],
                $student->id,
                $data['makeup_teacher_id'],
                $data['makeup_room_id'] ?? null
            );
            if (!empty($conflicts)) {
                return back()->withInput()->with('error', 'ไม่สามารถจองเวลาเรียนชดเชยได้: ' . implode(' / ', $conflicts));
            }
        }

        $leave = null;

        DB::transaction(function () use ($request, $data, $student, $enrollment, &$leave) {
            $submitterLabel = $request->user()->isAdmin() ? (auth()->user()->name ?? 'แอดมิน') : $request->user()->displayName();

            $leave = $student->leaves()->create([
                'enrollment_id'       => $data['enrollment_id'],
                'class_schedule_id'   => $data['class_schedule_id'] ?? null,
                'leave_type'          => $data['leave_type'],
                'leave_date'          => $data['leave_date'],
                'reason'              => $data['reason'] ?? null,
                'status'              => 'pending',
                'is_makeup_required'  => $data['leave_type'] === 'normal',
                'makeup_status'       => $data['leave_type'] === 'normal' ? 'pending' : 'not_required',
            ]);

            if ($data['leave_type'] === 'normal') {
                $validityDays = config('leave.makeup_validity_days', 30);
                $daysBetween = Carbon::parse($data['leave_date'])->diffInDays(Carbon::parse($data['makeup_date']), false);
                $isOverdue = $daysBetween > $validityDays;

                $makeup = MakeupRequest::create([
                    'student_leave_id'           => $leave->id,
                    'student_id'                 => $student->id,
                    'enrollment_id'               => $data['enrollment_id'],
                    'original_class_schedule_id' => $data['class_schedule_id'] ?? null,
                    'teacher_id'                  => $data['makeup_teacher_id'],
                    'room_id'                     => $data['makeup_room_id'] ?? null,
                    'makeup_date'                 => $data['makeup_date'],
                    'start_time'                  => $data['makeup_start_time'],
                    'end_time'                    => $data['makeup_end_time'],
                    'delivery_mode'               => $data['makeup_delivery_mode'],
                    'is_overdue'                  => $isOverdue,
                    'created_by'                  => $submitterLabel,
                ]);

                AppNotification::notifyAdmins(
                    'คำขอเรียนชดเชยใหม่ (รอ 2 ฝ่ายอนุมัติ)',
                    "{$student->full_name} ขอเรียนชดเชยวันที่ {$makeup->makeup_date->format('d/m/Y')} " . ($isOverdue ? '⚠️ เกินกำหนดนโยบาย' : '') . " (แจ้งโดย: {$submitterLabel})",
                    route('makeup-requests.show', $makeup)
                );
                if ($isOverdue) {
                    AppNotification::notifyAdmins(
                        '⚠️ คำขอเรียนชดเชยเกินกำหนดนโยบาย',
                        "{$student->full_name} ขอเรียนชดเชยห่างจากวันลาเกิน {$validityDays} วัน กรุณาตรวจสอบเป็นพิเศษ",
                        route('makeup-requests.show', $makeup)
                    );
                }
                AppNotification::notifyTeacher(
                    $data['makeup_teacher_id'],
                    'คำขอสอนชดเชยรออนุมัติจากคุณ',
                    "{$student->full_name} ขอเรียนชดเชยวันที่ {$makeup->makeup_date->format('d/m/Y')} {$makeup->start_time}-{$makeup->end_time}",
                    route('makeup-requests.show', $makeup)
                );
            } else {
                $leaveTypeLabel = $leave->leaveTypeLabel();
                AppNotification::notifyAdmins(
                    'คำขอลาเรียนใหม่',
                    "{$student->full_name} ขอ{$leaveTypeLabel} วันที่ " . $leave->leave_date->format('d/m/Y') . " (แจ้งโดย: {$submitterLabel})",
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
            }
        });

        return back()->with('success', $data['leave_type'] === 'normal'
            ? 'ส่งคำขอลา + คำขอเรียนชดเชยเรียบร้อยแล้ว รออนุมัติจาก Admin และอาจารย์ผู้สอนชดเชย'
            : 'ส่งคำขอลาเรียบร้อยแล้ว รอการอนุมัติ');
    }

    // POST /students/{student}/leaves/{leave}/approve — Admin เท่านั้น
    public function approve(Request $request, Student $student, StudentLeave $leave)
    {
        abort_if($leave->student_id !== $student->id, 404);
        if ($leave->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกดำเนินการไปแล้ว');
        }

        $leave->update(['status' => 'approved', 'reviewed_by' => auth()->user()->name ?? 'แอดมิน', 'reviewed_at' => now()]);

        if ($leave->class_schedule_id) {
            $leave->classSchedule?->update(['status' => 'cancelled']);
        }

        AppNotification::notifyStudentAndGuardians(
            $student,
            'คำขอลาเรียนได้รับการอนุมัติ',
            "คำขอ{$leave->leaveTypeLabel()}วันที่ {$leave->leave_date->format('d/m/Y')} ได้รับการอนุมัติแล้ว",
            route('leaves.index')
        );

        return back()->with('success', 'อนุมัติคำขอลาเรียบร้อยแล้ว');
    }

    // POST /students/{student}/leaves/{leave}/reject — Admin เท่านั้น
    public function reject(Request $request, Student $student, StudentLeave $leave)
    {
        abort_if($leave->student_id !== $student->id, 404);
        if ($leave->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกดำเนินการไปแล้ว');
        }

        $leave->update(['status' => 'rejected', 'reviewed_by' => auth()->user()->name ?? 'แอดมิน', 'reviewed_at' => now()]);
        $leave->makeupRequest?->update(['overall_status' => 'cancelled']);

        AppNotification::notifyStudentAndGuardians(
            $student,
            'คำขอลาเรียนถูกปฏิเสธ',
            "คำขอ{$leave->leaveTypeLabel()}วันที่ {$leave->leave_date->format('d/m/Y')} ไม่ได้รับการอนุมัติ กรุณาติดต่อโรงเรียนหากมีข้อสงสัย",
            route('leaves.index')
        );

        return back()->with('success', 'ปฏิเสธคำขอลาแล้ว');
    }

    // PATCH /students/{student}/leaves/{leave}/makeup — Admin เท่านั้น
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

    // ตรวจสิทธิ์: Admin ทำได้เสมอ / นักเรียนแจ้งได้เฉพาะบัญชีของตัวเอง / ผู้ปกครองแจ้งได้เฉพาะลูกของตัวเอง
    private function authorizeSelfOrGuardianOrAdmin(Request $request, Student $student): void
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return;
        }
        if ($user->isStudent() && $user->student_id === $student->id) {
            return;
        }
        if ($user->isGuardian() && $user->guardian?->students->pluck('id')->contains($student->id)) {
            return;
        }

        abort(403, 'คุณสามารถแจ้งลาให้ตัวเองหรือบุตรหลานที่ผูกกับบัญชีของคุณเท่านั้น');
    }
}
