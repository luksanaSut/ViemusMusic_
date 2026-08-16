<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRescheduleRequestRequest;
use App\Models\AppNotification;
use App\Models\ClassSchedule;
use App\Models\Room;
use App\Models\RescheduleRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RescheduleRequestController extends Controller
{
    // GET /reschedule-requests — ประวัติ + รายการรออนุมัติ (Admin เห็นทั้งหมด)
    public function index(Request $request)
    {
        $requests = RescheduleRequest::with(['classSchedule.enrollment.student', 'classSchedule.enrollment.course', 'swapWithClassSchedule.enrollment.student'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderByRaw("status = 'pending' desc")
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('reschedule-requests.index', compact('requests'));
    }

    // GET /reschedule-requests/create?class_schedule_id=
    public function create(Request $request)
    {
        $user = $request->user();

        $schedulesQuery = ClassSchedule::with(['enrollment.student', 'enrollment.course', 'teacher', 'room'])
            ->where('status', '!=', 'cancelled')
            ->where('schedule_date', '>=', now()->toDateString());

        // อาจารย์เห็นแค่คาบสอนของตัวเอง / Admin เห็นทั้งหมด
        if ($user->isTeacher() && $user->teacher_id) {
            $schedulesQuery->where('teacher_id', $user->teacher_id);
        }

        $schedules = $schedulesQuery->orderBy('schedule_date')->get();
        $teachers = Teacher::where('is_active', true)->orderBy('full_name')->get();
        $rooms = Room::where('is_active', true)->orderBy('name')->get();

        $preselectedId = $request->get('class_schedule_id');

        return view('reschedule-requests.create', compact('schedules', 'teachers', 'rooms', 'preselectedId'));
    }

    // POST /reschedule-requests
    public function store(StoreRescheduleRequestRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $schedule = ClassSchedule::with(['enrollment.student', 'enrollment.course'])->findOrFail($data['class_schedule_id']);

        // อาจารย์ขอเปลี่ยนได้เฉพาะคาบสอนของตัวเองเท่านั้น
        if ($user->isTeacher() && $user->teacher_id !== $schedule->teacher_id) {
            abort(403, 'คุณสามารถขอเปลี่ยนแปลงได้เฉพาะคาบสอนของตัวเองเท่านั้น');
        }

        $isAdminSubmitting = $user->isAdmin();

        $reschedule = null;
        DB::transaction(function () use ($data, $schedule, $user, $isAdminSubmitting, &$reschedule) {
            $reschedule = RescheduleRequest::create([
                'type'                        => $data['type'],
                'class_schedule_id'           => $schedule->id,
                'swap_with_class_schedule_id' => $data['swap_with_class_schedule_id'] ?? null,
                'new_teacher_id'              => $data['new_teacher_id'] ?? null,
                'new_room_id'                 => $data['new_room_id'] ?? null,
                'new_date'                    => $data['new_date'] ?? null,
                'new_start_time'              => $data['new_start_time'] ?? null,
                'new_end_time'                => $data['new_end_time'] ?? null,
                'snapshot_before'             => [
                    'teacher_id' => $schedule->teacher_id,
                    'room_id' => $schedule->room_id,
                    'schedule_date' => $schedule->schedule_date->toDateString(),
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                ],
                'status'       => $isAdminSubmitting ? 'approved' : 'pending',
                'reason'       => $data['reason'] ?? null,
                'requested_by' => $user->displayName(),
                'reviewed_by'  => $isAdminSubmitting ? $user->displayName() : null,
                'reviewed_at'  => $isAdminSubmitting ? now() : null,
            ]);

            // Admin ยื่นเอง = อนุมัติอัตโนมัติ ใช้ผลทันที
            if ($isAdminSubmitting) {
                $this->applyReschedule($reschedule);
            }
        });

        $this->notifyStakeholders($reschedule, $isAdminSubmitting ? 'applied' : 'submitted');

        return redirect()->route('reschedule-requests.index')->with(
            'success',
            $isAdminSubmitting ? 'เปลี่ยนแปลงตารางเรียนเรียบร้อยแล้ว' : 'ส่งคำขอเปลี่ยนแปลงตารางเรียนแล้ว รอ Admin อนุมัติ'
        );
    }

    // POST /reschedule-requests/{rescheduleRequest}/approve — Admin เท่านั้น
    public function approve(Request $request, RescheduleRequest $rescheduleRequest)
    {
        if ($rescheduleRequest->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกดำเนินการไปแล้ว');
        }

        DB::transaction(function () use ($request, $rescheduleRequest) {
            $rescheduleRequest->update([
                'status'      => 'approved',
                'reviewed_by' => $request->user()->displayName(),
                'reviewed_at' => now(),
            ]);
            $this->applyReschedule($rescheduleRequest);
        });

        $this->notifyStakeholders($rescheduleRequest->fresh(), 'applied');

        return back()->with('success', 'อนุมัติและปรับตารางเรียนเรียบร้อยแล้ว');
    }

    // POST /reschedule-requests/{rescheduleRequest}/reject — Admin เท่านั้น
    public function reject(Request $request, RescheduleRequest $rescheduleRequest)
    {
        if ($rescheduleRequest->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกดำเนินการไปแล้ว');
        }

        $data = $request->validate(['rejection_reason' => ['nullable', 'string', 'max:500']]);

        $rescheduleRequest->update([
            'status'            => 'rejected',
            'reviewed_by'       => $request->user()->displayName(),
            'reviewed_at'       => now(),
            'rejection_reason'  => $data['rejection_reason'] ?? null,
        ]);

        $this->notifyStakeholders($rescheduleRequest, 'rejected');

        return back()->with('success', 'ปฏิเสธคำขอเปลี่ยนแปลงตารางเรียนแล้ว');
    }

    // GET /reschedule-requests/check-conflict — ตรวจตารางว่างแบบ real-time ก่อนส่งคำขอ
    public function checkConflict(Request $request)
    {
        $data = $request->validate([
            'class_schedule_id' => ['required', 'exists:class_schedules,id'],
            'teacher_id'        => ['nullable'],
            'room_id'           => ['nullable'],
            'date'              => ['required', 'date'],
            'start_time'        => ['required'],
            'end_time'          => ['required'],
        ]);

        $schedule = ClassSchedule::with('enrollment')->find($data['class_schedule_id']);
        $teacherId = $data['teacher_id'] ?: $schedule->teacher_id;
        $roomId = $data['room_id'] ?: $schedule->room_id;

        $conflicts = ClassSchedule::findConflicts(
            $data['date'],
            $data['start_time'],
            $data['end_time'],
            $schedule->enrollment->student_id,
            $teacherId,
            $roomId,
            $schedule->id
        );

        return response()->json(['conflicts' => $conflicts]);
    }

    // ===== ใช้ผลจริงกับ ClassSchedule เมื่ออนุมัติแล้ว =====
    private function applyReschedule(RescheduleRequest $reschedule): void
    {
        $schedule = $reschedule->classSchedule;

        if ($reschedule->type === 'swap') {
            $other = $reschedule->swapWithClassSchedule;

            // สลับข้อมูลอาจารย์/ห้อง/วันเวลา ระหว่าง 2 คาบทั้งหมด
            $temp = [
                'teacher_id'    => $schedule->teacher_id,
                'room_id'       => $schedule->room_id,
                'schedule_date' => $schedule->schedule_date,
                'start_time'    => $schedule->start_time,
                'end_time'      => $schedule->end_time,
            ];

            $schedule->update([
                'teacher_id'    => $other->teacher_id,
                'room_id'       => $other->room_id,
                'schedule_date' => $other->schedule_date,
                'start_time'    => $other->start_time,
                'end_time'      => $other->end_time,
            ]);

            $other->update($temp);
        } else {
            $schedule->update([
                'teacher_id'    => $reschedule->new_teacher_id ?: $schedule->teacher_id,
                'room_id'       => $reschedule->new_room_id,
                'schedule_date' => $reschedule->new_date,
                'start_time'    => $reschedule->new_start_time,
                'end_time'      => $reschedule->new_end_time,
            ]);
        }
    }

    // ===== แจ้งเตือนผู้เกี่ยวข้องทุกฝ่าย =====
    private function notifyStakeholders(RescheduleRequest $reschedule, string $event): void
    {
        $schedule = $reschedule->classSchedule()->with(['enrollment.student', 'enrollment.course', 'teacher'])->first();
        $student = $schedule->enrollment->student;
        $courseName = $schedule->enrollment->course->name ?? '-';

        $title = match ($event) {
            'submitted' => 'คำขอเปลี่ยนแปลงตารางเรียนใหม่ (รออนุมัติ)',
            'applied'   => $reschedule->type === 'swap' ? 'แลกคาบเรียนสำเร็จ' : 'ปรับตารางเรียนสำเร็จ',
            'rejected'  => 'คำขอเปลี่ยนแปลงตารางเรียนถูกปฏิเสธ',
        };
        $message = "{$reschedule->typeLabel()} สำหรับ {$student->full_name} คอร์ส {$courseName}";

        if ($event === 'submitted') {
            AppNotification::notifyAdmins($title, $message, route('reschedule-requests.index'));
        }

        // แจ้งอาจารย์เดิม + อาจารย์ใหม่ (ถ้ามีการเปลี่ยน)
        if ($schedule->teacher_id) {
            AppNotification::notifyTeacher($schedule->teacher_id, $title, $message, route('reschedule-requests.index'));
        }
        if ($reschedule->new_teacher_id && $reschedule->new_teacher_id !== $schedule->teacher_id) {
            AppNotification::notifyTeacher($reschedule->new_teacher_id, $title, $message, route('reschedule-requests.index'));
        }

        // แลกคาบ: แจ้งอาจารย์ของคาบที่แลกด้วย
        if ($reschedule->type === 'swap' && $reschedule->swapWithClassSchedule?->teacher_id) {
            AppNotification::notifyTeacher($reschedule->swapWithClassSchedule->teacher_id, $title, $message, route('reschedule-requests.index'));
        }

        AppNotification::notifyStudentAndGuardians($student, $title, $message, route('leaves.index'));
    }
}
