<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\ClassSchedule;
use App\Models\StudentLeave;
use App\Models\Teacher;
use App\Models\TeachingLog;
use App\Models\TeachingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeachingLogController extends Controller
{
    // GET /teaching-logs — ประวัติการเข้าเรียนทั้งหมด + ค้นหา + รายการที่รอเช็คชื่อ
    public function index(Request $request)
    {
        $user = $request->user();

        $logs = TeachingLog::with(['classSchedule', 'student', 'teacher', 'enrollment.course'])
            ->when($user->isTeacher() && $user->teacher_id, fn($q) => $q->where('teacher_id', $user->teacher_id))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = $request->q;
                $q->whereHas('student', fn($qq) => $qq->where('full_name', 'like', "%{$term}%"))
                    ->orWhereHas('teacher', fn($qq) => $qq->where('full_name', 'like', "%{$term}%"));
            })
            ->when($request->filled('status'), fn($q) => $q->where('attendance_status', $request->status))
            ->when($request->filled('date_from'), fn($q) => $q->whereHas('classSchedule', fn($qq) => $qq->where('schedule_date', '>=', $request->date_from)))
            ->when($request->filled('date_to'), fn($q) => $q->whereHas('classSchedule', fn($qq) => $qq->where('schedule_date', '<=', $request->date_to)))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        // ===== คาบที่ยังไม่เคยเปิดเช็คชื่อเลย (ยังไม่มี TeachingLog) แสดงให้กดเข้าไปเช็คชื่อได้ทันที =====
        $pendingSchedules = ClassSchedule::with(['enrollment.student', 'enrollment.course', 'teacher'])
            ->where('status', 'scheduled')
            ->where('schedule_date', '<=', now()->toDateString()) // เฉพาะคาบที่ถึงวันแล้วหรือผ่านไปแล้ว
            ->doesntHave('teachingLog')
            ->when($user->isTeacher() && $user->teacher_id, fn($q) => $q->where('teacher_id', $user->teacher_id))
            ->orderByDesc('schedule_date')
            ->limit(20)
            ->get();

        return view('teaching-logs.index', compact('logs', 'pendingSchedules'));
    }

    // GET /schedules/{classSchedule}/attendance — หน้าเช็คชื่อ + ยืนยันเวลาสอนของคาบนั้น
    public function show(Request $request, ClassSchedule $classSchedule)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id !== $classSchedule->teacher_id) {
            abort(403, 'คุณสามารถเช็คชื่อได้เฉพาะคาบสอนของตัวเองเท่านั้น');
        }

        $classSchedule->load(['enrollment.student', 'enrollment.course', 'teacher', 'room']);

        $log = TeachingLog::firstOrCreate(
            ['class_schedule_id' => $classSchedule->id],
            [
                'enrollment_id' => $classSchedule->enrollment_id,
                'teacher_id'    => $classSchedule->teacher_id,
                'student_id'    => $classSchedule->enrollment->student_id,
            ]
        );

        // Business rule: เชื่อมโยงกับระบบลาเรียน — ถ้ามีคำขอลาที่อนุมัติแล้วผูกกับคาบนี้ ให้ตั้งสถานะอัตโนมัติ
        $approvedLeave = StudentLeave::where('class_schedule_id', $classSchedule->id)
            ->where('status', 'approved')->first();

        if ($approvedLeave && !$log->attendance_status) {
            $log->update([
                'attendance_status' => 'excused_leave',
                'student_leave_id'  => $approvedLeave->id,
                'checked_in_at'     => now(),
                'checked_in_by'     => 'ระบบ (เชื่อมโยงจากคำขอลาที่อนุมัติแล้ว)',
            ]);
            $log->refresh();
        }

        return view('teaching-logs.show', compact('classSchedule', 'log', 'approvedLeave'));
    }

    // POST /teaching-logs/{teachingLog}/check-in — เช็คชื่อ + ระบุสถานะการเข้าเรียน
    public function checkIn(Request $request, TeachingLog $teachingLog)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id !== $teachingLog->teacher_id) {
            abort(403);
        }

        // ถ้าคาบนี้ผูกกับคำขอลาที่อนุมัติแล้ว ห้ามเปลี่ยนสถานะเป็นอย่างอื่นนอกจาก excused_leave
        if ($teachingLog->student_leave_id) {
            return back()->with('error', 'คาบนี้มีคำขอลาที่อนุมัติแล้วผูกอยู่ ไม่สามารถเปลี่ยนสถานะการเข้าเรียนได้ — แก้ไขที่คำขอลาแทน');
        }

        $data = $request->validate([
            'attendance_status' => ['required', 'in:present,late,absent'],
            'notes'              => ['nullable', 'string', 'max:500'],
        ]);

        $teachingLog->update([
            'attendance_status' => $data['attendance_status'],
            'checked_in_at'     => now(),
            'checked_in_by'     => $user->displayName(),
            'notes'             => $data['notes'] ?? $teachingLog->notes,
        ]);

        return back()->with('success', 'บันทึกการเช็คชื่อเรียบร้อยแล้ว: ' . $teachingLog->attendanceStatusLabel());
    }

    // POST /teaching-logs/{teachingLog}/confirm-duration — Confirm เวลาที่จะสอนจริง (30/45/60/สอนเพิ่ม)
    public function confirmDuration(Request $request, TeachingLog $teachingLog)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id !== $teachingLog->teacher_id) {
            abort(403);
        }
        if (!$teachingLog->attendance_status) {
            return back()->with('error', 'กรุณาเช็คชื่อก่อนยืนยันเวลาสอนจริง');
        }
        if ($teachingLog->confirmed_at) {
            return back()->with('error', 'คาบนี้ยืนยันเวลาสอนไปแล้ว');
        }

        $data = $request->validate([
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'is_extra_time'    => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($request, $data, $teachingLog, $user) {
            $classSchedule = $teachingLog->classSchedule;
            $enrollment = $teachingLog->enrollment;
            $teacher = $teachingLog->teacher;

            $teachingLog->update([
                'confirmed_duration_minutes' => $data['duration_minutes'],
                'is_extra_time'              => $request->boolean('is_extra_time'),
                'confirmed_at'               => now(),
                'confirmed_by'               => $user->displayName(),
            ]);

            // ===== Business rule: เชื่อมโยงกับระบบเงินเดือน (สร้างรายการ teaching_sessions ให้อาจารย์) =====
            $hours = round($data['duration_minutes'] / 60, 2);
            $rate = $teacher->rates()
                ->where('is_active', true)
                ->where(fn($q) => $q->whereNull('instrument_id')->orWhere('instrument_id', $enrollment->course->instrument_id))
                ->orderByRaw('instrument_id IS NULL') // เรทเฉพาะเครื่องดนตรีมาก่อนเรทกลาง
                ->first();

            $rateApplied = $rate?->rate_amount ?? 0;
            $income = $rate && $rate->rate_type === 'per_hour' ? round($hours * $rateApplied, 2) : $rateApplied;

            $teachingSession = TeachingSession::create([
                'teacher_id'      => $teacher->id,
                'instrument_id'   => $enrollment->course->instrument_id,
                'teaching_type_id' => null,
                'level_id'        => null,
                'student_name'    => $enrollment->student->full_name,
                'session_date'    => $classSchedule->schedule_date,
                'start_time'      => $classSchedule->start_time,
                'end_time'        => $classSchedule->end_time,
                'hours'           => $hours,
                'rate_applied'    => $rateApplied,
                'transport_fee_applied' => 0,
                'income_amount'   => $income,
                'status'          => 'completed',
            ]);
            $teachingLog->update(['teaching_session_id' => $teachingSession->id]);

            // ===== Business rule: เชื่อมโยงกับตารางเรียน + การตัดคอร์ส =====
            // ตัดจำนวนครั้งเรียนเฉพาะกรณีเข้าเรียนจริง (present/late) เท่านั้น — ไม่ตัดถ้าลา/ขาดเรียน
            if (in_array($teachingLog->attendance_status, ['present', 'late']) && !$teachingLog->session_deducted) {
                $enrollment->deductSession();
                $teachingLog->update(['session_deducted' => true]);
            }

            // อัปเดตตารางเรียนเป็น "สอนแล้ว"
            $classSchedule->update(['status' => $teachingLog->attendance_status === 'absent' ? 'no_show' : 'completed']);
        });

        AppNotification::notifyStudentAndGuardians(
            $teachingLog->student,
            'บันทึกการสอนคาบนี้เสร็จสิ้น',
            "คาบเรียนวันที่ {$teachingLog->classSchedule->schedule_date->format('d/m/Y')} บันทึกผล: {$teachingLog->attendanceStatusLabel()}",
            route('teaching-logs.index')
        );

        return back()->with('success', 'ยืนยันเวลาสอนจริงเรียบร้อยแล้ว — บันทึกลงระบบเงินเดือนและตัดจำนวนครั้งเรียนแล้ว');
    }
}