<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\ClassSchedule;
use App\Models\MakeupRequest;
use App\Models\StudentLeave;
use App\Models\Teacher;
use App\Models\TeachingLog;
use App\Models\TeachingSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeachingLogController extends Controller
{
    // GET /teaching-logs — แดชบอร์ดเช็คชื่อเข้าเรียน แยกตามช่วงวัน/สัปดาห์/เดือน
    public function index(Request $request)
    {
        $user = $request->user();

        $range = in_array($request->get('range'), ['day', 'week', 'month']) ? $request->get('range') : 'day';

        $refDate = now();
        if ($request->filled('date')) {
            try {
                $refDate = \Carbon\Carbon::parse($request->get('date'))->startOfDay();
            } catch (\Exception $e) {
                $refDate = now();
            }
        }

        [$rangeStart, $rangeEnd, $rangeLabel, $prevDate, $nextDate, $isCurrentPeriod] = $this->resolveRange($range, $refDate);

        $schedules = ClassSchedule::with(['enrollment.student', 'enrollment.course', 'teacher', 'room', 'teachingLog'])
            ->whereIn('status', ['scheduled', 'completed', 'no_show'])
            ->whereBetween('schedule_date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->when($user->isTeacher() && $user->teacher_id, fn($q) => $q->where('teacher_id', $user->teacher_id))
            ->orderBy('schedule_date')
            ->orderBy('start_time')
            ->limit(500)
            ->get();

        $scheduleIds = $schedules->pluck('id');

        // คาบที่มีคำขอลาที่อนุมัติแล้วผูกอยู่ (แม้ยังไม่เคยเปิดหน้าเช็คชื่อจึงยังไม่มี TeachingLog ก็ต้องนับเป็น "ลา")
        $approvedLeaveScheduleIds = StudentLeave::where('status', 'approved')
            ->whereIn('class_schedule_id', $scheduleIds)
            ->pluck('class_schedule_id')
            ->flip();

        // คาบที่เป็นคาบเรียนชดเชย (ถูกสร้างจากคำขอสอนชดเชยที่อนุมัติแล้ว)
        $makeupScheduleIds = MakeupRequest::whereIn('class_schedule_id', $scheduleIds)
            ->pluck('class_schedule_id')
            ->flip();

        $stats = ['total' => $schedules->count(), 'pending' => 0, 'checked' => 0, 'absent' => 0, 'leave' => 0, 'makeup' => 0];
        $pendingItems = collect();
        $historyItems = collect();

        foreach ($schedules as $s) {
            $log = $s->teachingLog;
            $status = $log->attendance_status ?? null;
            $isMakeup = $makeupScheduleIds->has($s->id);
            $hasApprovedLeave = $approvedLeaveScheduleIds->has($s->id);

            $bucket = match (true) {
                $hasApprovedLeave || $status === 'excused_leave' => 'leave',
                $status === 'absent' => 'absent',
                in_array($status, ['present', 'late']) => 'checked',
                default => 'pending',
            };

            $stats[$bucket]++;
            if ($isMakeup) $stats['makeup']++;

            $s->uiBucket = $bucket;
            $s->isMakeup = $isMakeup;

            // ยังต้องดำเนินการต่อจนกว่าจะยืนยันเวลาสอนจริงแล้ว (แก้ไขไม่ได้อีก) — ถึงจะถือว่าเสร็จสมบูรณ์
            if (!$log || !$log->confirmed_at) {
                $pendingItems->push($s);
            } else {
                $historyItems->push($s);
            }
        }

        $branches = $schedules->pluck('teacher.branch')->filter()->unique()->sort()->values();

        $teacherLabel = ($user->isTeacher() && $user->teacher)
            ? 'ครู' . ($user->teacher->nickname ?: $user->teacher->full_name)
            : $user->displayName();

        return view('teaching-logs.index', compact(
            'range',
            'rangeLabel',
            'teacherLabel',
            'stats',
            'pendingItems',
            'historyItems',
            'branches',
            'refDate',
            'prevDate',
            'nextDate',
            'isCurrentPeriod',
            'rangeStart'
        ));
    }

    // คำนวณช่วงวันที่ + ป้ายแสดงผลภาษาไทย (ปี พ.ศ.) + วันที่ก่อนหน้า/ถัดไป ตามมุมมองที่เลือก (รายวัน/สัปดาห์/เดือน)
    // โดยอิงจาก $refDate (วันที่ผู้ใช้เลือกไว้ ไม่ใช่วันนี้เสมอไป) เพื่อให้เลื่อนดูเดือน/สัปดาห์/วันอื่นได้
    private function resolveRange(string $range, \Carbon\Carbon $refDate): array
    {
        $today = now();
        $monthsShort = ['', 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
        $monthsFull = ['', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];

        if ($range === 'week') {
            $start = $refDate->copy()->startOfWeek();
            $end = $refDate->copy()->endOfWeek();
            $label = "{$start->day} {$monthsShort[$start->month]} - {$end->day} {$monthsShort[$end->month]} " . ($end->year + 543);
            $prev = $start->copy()->subWeek();
            $next = $start->copy()->addWeek();
            $isCurrent = $today->between($start, $end);
        } elseif ($range === 'month') {
            $start = $refDate->copy()->startOfMonth();
            $end = $refDate->copy()->endOfMonth();
            $label = $monthsFull[$start->month] . ' ' . ($start->year + 543);
            $prev = $start->copy()->subMonthNoOverflow();
            $next = $start->copy()->addMonthNoOverflow();
            $isCurrent = $today->isSameMonth($start) && $today->isSameYear($start);
        } else {
            $start = $refDate->copy()->startOfDay();
            $end = $refDate->copy()->endOfDay();
            $label = "{$start->day} {$monthsShort[$start->month]} " . ($start->year + 543);
            $prev = $start->copy()->subDay();
            $next = $start->copy()->addDay();
            $isCurrent = $today->isSameDay($start);
        }

        return [$start, $end, $label, $prev, $next, $isCurrent];
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
            'km_traveled'       => ['nullable', 'numeric', 'min:0', 'max:1000'],
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

            if ($rate && $rate->rate_type === 'per_hour') {
                $income = round($hours * $rateApplied, 2);
            } elseif ($rate && $rate->rate_type === 'percentage') {
                // รายเปอร์เซ็นต์: คิดจากราคาคอร์สต่อครั้ง (ราคาคอร์ส / จำนวนครั้งทั้งหมด) x เปอร์เซ็นต์ที่ตั้งไว้
                $course = $enrollment->course;
                $pricePerSession = ($course && $course->total_sessions) ? ($course->price / $course->total_sessions) : ($course->price ?? 0);
                $income = round($pricePerSession * ($rateApplied / 100), 2);
            } else {
                $income = $rateApplied; // per_session / monthly_fixed
            }

            $transportFee = $this->calculateTransportFee($teacher, $classSchedule->schedule_date->toDateString(), $data['km_traveled'] ?? null);

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
                'transport_fee_applied' => $transportFee,
                'km_traveled'     => $data['km_traveled'] ?? null,
                'income_amount'   => $income,
                'status'          => 'completed',
            ]);

            // สำคัญ: บังคับ hours/income ให้ตรงกับเวลาสอนจริงที่อาจารย์ยืนยันเสมอ
            // กันกรณีโมเดล TeachingSession มี boot()/mutator ที่คำนวณ hours จาก start_time-end_time ของตารางเรียน
            // ซ้ำทับค่าที่คำนวณจาก duration_minutes จริงไปแล้ว (สาเหตุของค่าติดลบ/ค่าผิดที่เจอ)
            \Illuminate\Support\Facades\DB::table('teaching_sessions')
                ->where('id', $teachingSession->id)
                ->update(['hours' => $hours, 'income_amount' => $income]);

            $teachingSession->refresh();

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

    // คำนวณค่ารถตามเงื่อนไขที่ตั้งไว้ให้อาจารย์แต่ละคน
    // Business rule: คำนวณเฉพาะคลาสที่สอนจริงเท่านั้น (เมธอดนี้ถูกเรียกจากจุดเดียวคือตอนยืนยันเวลาสอนจริงสำเร็จ)
    private function calculateTransportFee($teacher, string $sessionDate, ?float $kmTraveled): float
    {
        $fee = $teacher->activeTransportFee();
        if (!$fee) return 0;

        if ($fee->fee_type === 'per_km') {
            return $kmTraveled ? round($kmTraveled * $fee->fee_amount, 2) : 0;
        }

        // fixed_per_day: จ่ายแค่ 1 ครั้งต่อวัน แม้จะสอนหลายคาบในวันเดียวกัน
        $alreadyPaidToday = \App\Models\TeachingSession::where('teacher_id', $teacher->id)
            ->whereDate('session_date', $sessionDate)
            ->where('transport_fee_applied', '>', 0)
            ->exists();

        return $alreadyPaidToday ? 0 : (float) $fee->fee_amount;
    }
}