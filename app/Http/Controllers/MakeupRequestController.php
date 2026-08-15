<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\ClassSchedule;
use App\Models\MakeupRequest;
use App\Models\Room;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MakeupRequestController extends Controller
{
    // GET /makeup-requests — รายการคำขอเรียนชดเชยทั้งหมด (admin)
    public function index(Request $request)
    {
        $requests = MakeupRequest::with(['student', 'teacher', 'enrollment.course'])
            ->when($request->filled('status'), fn($q) => $q->where('overall_status', $request->status))
            ->when($request->boolean('overdue_only'), fn($q) => $q->where('is_overdue', true))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('makeup-requests.index', compact('requests'));
    }

    // GET /my-makeup-requests — หน้ารายการคำขอเรียนชดเชยที่มอบหมายให้อาจารย์คนนี้สอน
    public function myIndex(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 404, 'บัญชีนี้ยังไม่ได้ผูกกับข้อมูลอาจารย์');

        $requests = MakeupRequest::where('teacher_id', $teacher->id)
            ->with(['student', 'enrollment.course'])
            ->orderByRaw("instructor_approval_status = 'pending' desc")
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('makeup-requests.my-index', compact('teacher', 'requests'));
    }

    // GET /makeup-requests/{makeupRequest}
    public function show(Request $request, MakeupRequest $makeupRequest)
    {
        // อาจารย์ดูได้เฉพาะคำขอที่ผูกกับตัวเอง, admin ดูได้ทุกคำขอ
        if ($request->user()->isTeacher() && $request->user()->teacher_id !== $makeupRequest->teacher_id) {
            abort(403);
        }

        $makeupRequest->load(['student', 'teacher', 'room', 'enrollment.course', 'studentLeave', 'originalClassSchedule']);

        return view('makeup-requests.show', compact('makeupRequest'));
    }

    // GET /makeup-requests-check-conflict — ตรวจสอบตารางซ้ำ + Availability ของอาจารย์ แบบ real-time (ใช้ตอนแจ้งลา)
    public function checkConflict(Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'teacher_id' => ['required', 'exists:teachers,id'],
            'room_id'    => ['nullable'],
            'date'       => ['required', 'date'],
            'start_time' => ['required'],
            'end_time'   => ['required'],
        ]);

        $user = $request->user();
        if (!$user->isAdmin()) {
            $allowed = ($user->isStudent() && $user->student_id == $data['student_id'])
                || ($user->isGuardian() && $user->guardian?->students->pluck('id')->contains($data['student_id']));
            abort_unless($allowed, 403);
        }

        $conflicts = ClassSchedule::findConflicts(
            $data['date'],
            $data['start_time'],
            $data['end_time'],
            $data['student_id'],
            $data['teacher_id'],
            $data['room_id'] ?? null
        );

        // ===== ตรวจ Availability ของอาจารย์ด้วย ไม่ใช่แค่ตารางชนกัน =====
        $teacher = Teacher::find($data['teacher_id']);
        $dayOfWeek = \Carbon\Carbon::parse($data['date'])->dayOfWeek;
        if ($teacher && !$teacher->isAvailableAt($dayOfWeek, $data['start_time'], $data['end_time'])) {
            $conflicts[] = 'อาจารย์ที่เลือกไม่ได้ตั้งเวลาว่างไว้ในช่วงเวลานี้ (นอกตาราง Availability)';
        }

        return response()->json(['conflicts' => $conflicts]);
    }

    // POST /makeup-requests/{makeupRequest}/approve-admin
    public function approveByAdmin(Request $request, MakeupRequest $makeupRequest)
    {
        if ($makeupRequest->admin_approval_status !== 'pending') {
            return back()->with('error', 'รายการนี้ถูกดำเนินการโดย Admin ไปแล้ว');
        }

        // ===== Validation rule: ตรวจสอบสิทธิ์การเรียนชดเชยก่อนอนุมัติอีกครั้ง ณ เวลาจริง =====
        if (!$makeupRequest->enrollment->course->allow_makeup_class) {
            return back()->with('error', 'คอร์สนี้ไม่เปิดสิทธิ์เรียนชดเชย ไม่สามารถอนุมัติได้');
        }
        $conflicts = ClassSchedule::findConflicts(
            $makeupRequest->makeup_date->toDateString(),
            $makeupRequest->start_time,
            $makeupRequest->end_time,
            $makeupRequest->student_id,
            $makeupRequest->teacher_id,
            $makeupRequest->room_id
        );
        if (!empty($conflicts)) {
            return back()->with('error', 'ไม่สามารถอนุมัติได้ เนื่องจากมีตารางซ้ำเกิดขึ้นแล้ว: ' . implode(' / ', $conflicts));
        }

        $makeupRequest->update([
            'admin_approval_status' => 'approved',
            'admin_reviewed_by'     => auth()->user()->name ?? 'แอดมิน',
            'admin_reviewed_at'     => now(),
        ]);

        $this->finalizeIfFullyApproved($makeupRequest);
        $this->notifyStudentSideOnStatusChange($makeupRequest->fresh(), 'admin');

        return back()->with('success', 'Admin อนุมัติคำขอเรียนชดเชยแล้ว' . ($makeupRequest->fresh()->isFullyApproved() ? ' (อนุมัติครบ 2 ฝ่าย จัดตารางเรียนให้แล้ว)' : ' — รออาจารย์อนุมัติอีกฝ่าย'));
    }

    // POST /makeup-requests/{makeupRequest}/approve-instructor
    public function approveByInstructor(Request $request, MakeupRequest $makeupRequest)
    {
        if ($request->user()->isTeacher() && $request->user()->teacher_id !== $makeupRequest->teacher_id) {
            abort(403, 'คุณสามารถอนุมัติเฉพาะคำขอที่ผูกกับตัวเองเท่านั้น');
        }
        if ($makeupRequest->instructor_approval_status !== 'pending') {
            return back()->with('error', 'รายการนี้ถูกดำเนินการโดยอาจารย์ไปแล้ว');
        }

        // ===== Validation rule: ตรวจสอบสิทธิ์การเรียนชดเชยก่อนอนุมัติ (เหมือนที่ Admin เช็ค เผื่อเงื่อนไขคอร์สเปลี่ยนไประหว่างรออนุมัติ) =====
        if (!$makeupRequest->enrollment->course->allow_makeup_class) {
            return back()->with('error', 'คอร์สนี้ไม่เปิดสิทธิ์เรียนชดเชยแล้ว (เงื่อนไขอาจถูกเปลี่ยนหลังส่งคำขอ) ไม่สามารถอนุมัติได้ กรุณาแจ้ง Admin');
        }

        $conflicts = ClassSchedule::findConflicts(
            $makeupRequest->makeup_date->toDateString(),
            $makeupRequest->start_time,
            $makeupRequest->end_time,
            $makeupRequest->student_id,
            $makeupRequest->teacher_id,
            $makeupRequest->room_id
        );
        if (!empty($conflicts)) {
            return back()->with('error', 'ไม่สามารถอนุมัติได้ เนื่องจากมีตารางซ้ำเกิดขึ้นแล้ว: ' . implode(' / ', $conflicts));
        }

        $makeupRequest->update([
            'instructor_approval_status' => 'approved',
            'instructor_reviewed_at'     => now(),
        ]);

        $this->finalizeIfFullyApproved($makeupRequest);
        $this->notifyStudentSideOnStatusChange($makeupRequest->fresh(), 'instructor');

        return back()->with('success', 'อนุมัติคำขอสอนชดเชยแล้ว' . ($makeupRequest->fresh()->isFullyApproved() ? ' (อนุมัติครบ 2 ฝ่าย จัดตารางเรียนให้แล้ว)' : ' — รอ Admin อนุมัติอีกฝ่าย'));
    }

    // POST /makeup-requests/{makeupRequest}/reject
    public function reject(Request $request, MakeupRequest $makeupRequest)
    {
        $isAdmin = $request->user()->isAdmin();
        $isAssignedInstructor = $request->user()->isTeacher() && $request->user()->teacher_id === $makeupRequest->teacher_id;
        abort_unless($isAdmin || $isAssignedInstructor, 403);

        $data = $request->validate(['rejection_reason' => ['nullable', 'string', 'max:500']]);

        DB::transaction(function () use ($makeupRequest, $isAdmin, $data) {
            $makeupRequest->update([
                'admin_approval_status'      => $isAdmin ? 'rejected' : $makeupRequest->admin_approval_status,
                'admin_reviewed_by'          => $isAdmin ? (auth()->user()->name ?? 'แอดมิน') : $makeupRequest->admin_reviewed_by,
                'admin_reviewed_at'          => $isAdmin ? now() : $makeupRequest->admin_reviewed_at,
                'instructor_approval_status' => !$isAdmin ? 'rejected' : $makeupRequest->instructor_approval_status,
                'instructor_reviewed_at'     => !$isAdmin ? now() : $makeupRequest->instructor_reviewed_at,
                'overall_status'             => 'rejected',
                'rejection_reason'           => $data['rejection_reason'] ?? null,
            ]);

            $makeupRequest->studentLeave?->update(['makeup_status' => 'pending']);
        });

        AppNotification::notifyAdmins(
            'คำขอเรียนชดเชยถูกปฏิเสธ',
            "คำขอเรียนชดเชยของ {$makeupRequest->student->full_name} ถูกปฏิเสธโดย " . ($isAdmin ? 'Admin' : 'อาจารย์ผู้สอนชดเชย'),
            route('makeup-requests.show', $makeupRequest)
        );
        AppNotification::notifyStudentAndGuardians(
            $makeupRequest->student,
            'คำขอเรียนชดเชยถูกปฏิเสธ',
            "คำขอเรียนชดเชยวันที่ {$makeupRequest->makeup_date->format('d/m/Y')} ไม่ได้รับการอนุมัติ กรุณาส่งคำขอวันเวลาใหม่",
            route('leaves.index')
        );

        return back()->with('success', 'ปฏิเสธคำขอเรียนชดเชยแล้ว — นักเรียนต้องส่งคำขอวันเวลาใหม่');
    }

    // เมื่ออนุมัติครบทั้ง 2 ฝ่ายแล้ว -> สร้างตารางเรียนจริงให้อัตโนมัติ (Business rule: อัปเดตตารางเรียนอัตโนมัติ)
    private function finalizeIfFullyApproved(MakeupRequest $makeupRequest): void
    {
        if (!$makeupRequest->isFullyApproved() || $makeupRequest->class_schedule_id) {
            return;
        }

        DB::transaction(function () use ($makeupRequest) {
            $schedule = ClassSchedule::create([
                'enrollment_id'  => $makeupRequest->enrollment_id,
                'teacher_id'     => $makeupRequest->teacher_id,
                'room_id'        => $makeupRequest->room_id,
                'schedule_date'  => $makeupRequest->makeup_date,
                'start_time'     => $makeupRequest->start_time,
                'end_time'       => $makeupRequest->end_time,
                'delivery_mode'  => $makeupRequest->delivery_mode,
                'status'         => 'scheduled',
                'notes'          => 'คาบเรียนชดเชย (Makeup Class) จากคำขอ #' . $makeupRequest->id,
                'created_by'     => 'ระบบ (อนุมัติเรียนชดเชยครบ 2 ฝ่าย)',
            ]);

            $makeupRequest->update(['overall_status' => 'approved', 'class_schedule_id' => $schedule->id]);
            $makeupRequest->studentLeave?->update(['makeup_status' => 'scheduled', 'makeup_date' => $makeupRequest->makeup_date]);
        });

        AppNotification::notifyAdmins(
            'จัดตารางเรียนชดเชยสำเร็จ',
            "คำขอเรียนชดเชยของ {$makeupRequest->student->full_name} ได้รับอนุมัติครบ 2 ฝ่าย และจัดตารางเรียนให้แล้ว",
            route('makeup-requests.show', $makeupRequest)
        );
        AppNotification::notifyStudentAndGuardians(
            $makeupRequest->student,
            'จัดตารางเรียนชดเชยให้แล้ว',
            "คำขอเรียนชดเชยได้รับอนุมัติครบทุกฝ่ายแล้ว จัดตารางเรียนวันที่ {$makeupRequest->makeup_date->format('d/m/Y')} {$makeupRequest->start_time}-{$makeupRequest->end_time} ให้เรียบร้อย",
            route('leaves.index')
        );
    }

    // แจ้งนักเรียน/ผู้ปกครองเมื่อฝ่ายใดฝ่ายหนึ่งอนุมัติแล้ว แต่ยังไม่ครบ 2 ฝ่าย (ให้รู้ความคืบหน้า)
    private function notifyStudentSideOnStatusChange(MakeupRequest $makeupRequest, string $approvedBy): void
    {
        if ($makeupRequest->isFullyApproved()) {
            return; // กรณีอนุมัติครบแล้ว ไปแจ้งใน finalizeIfFullyApproved() แทน กันแจ้งซ้ำ
        }

        $approverLabel = $approvedBy === 'admin' ? 'Admin' : 'อาจารย์ผู้สอนชดเชย';
        AppNotification::notifyStudentAndGuardians(
            $makeupRequest->student,
            'ความคืบหน้าคำขอเรียนชดเชย',
            "{$approverLabel}อนุมัติคำขอเรียนชดเชยวันที่ {$makeupRequest->makeup_date->format('d/m/Y')} แล้ว กำลังรออีกฝ่ายอนุมัติ",
            route('leaves.index')
        );
    }
}
