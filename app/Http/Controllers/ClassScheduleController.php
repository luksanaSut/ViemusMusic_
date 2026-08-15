<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassScheduleRequest;
use App\Models\ClassSchedule;
use App\Models\Enrollment;
use App\Models\Room;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ClassScheduleController extends Controller
{
    // GET /schedules
    public function index(Request $request)
    {
        $view = $request->get('view', 'week');
        $date = Carbon::parse($request->get('date', now()->toDateString()));

        [$from, $to] = match ($view) {
            'day'   => [$date->copy()->startOfDay(), $date->copy()->endOfDay()],
            'month' => [$date->copy()->startOfMonth(), $date->copy()->endOfMonth()],
            default => [$date->copy()->startOfWeek(), $date->copy()->endOfWeek()],
        };

        // สำคัญ: ใช้ withTrashed() กับนักเรียน/คอร์ส เพราะเป็น Soft Delete
        // ถ้าไม่ใส่ไว้ ตารางเรียนของนักเรียนที่ถูกลบจะดึงข้อมูลไม่ได้ (student เป็น null) ทำให้หน้าเว็บพังทั้งหน้า
        $schedules = ClassSchedule::with([
            'enrollment' => fn($q) => $q->with([
                'student' => fn($q2) => $q2->withTrashed(),
                'course'  => fn($q2) => $q2->withTrashed(),
            ]),
            'teacher',
            'room',
        ])
            ->whereBetween('schedule_date', [$from->toDateString(), $to->toDateString()])
            ->where('status', '!=', 'cancelled')
            ->search($request->get('q'))
            ->forStudent($request->get('student_id'))
            ->forTeacher($request->get('teacher_id'))
            ->forRoom($request->get('room_id'))
            ->orderBy('schedule_date')->orderBy('start_time')
            ->get()
            ->groupBy(fn($s) => $s->schedule_date->toDateString());

        $students = Student::orderBy('full_name')->get(['id', 'full_name', 'student_code']);
        $teachers = Teacher::where('is_active', true)->orderBy('full_name')->get(['id', 'full_name', 'nickname']);
        $rooms    = Room::where('is_active', true)->orderBy('name')->get(['id', 'name']);

        return view('schedules.index', compact('schedules', 'view', 'date', 'from', 'to', 'students', 'teachers', 'rooms'));
    }

    // GET /schedules/create
    public function create(Request $request)
    {
        // ใช้ withTrashed() เพราะ student/course เป็น Soft Delete — ถ้าไม่ใส่ enrollment ของนักเรียน/คอร์สที่ถูกลบ
        // จะดึงมาเป็น null ทำให้หน้าเว็บพังตอน Blade พยายามอ่าน ->full_name จาก null
        $enrollments = Enrollment::where('status', 'active')
            ->with([
                'student' => fn($q) => $q->withTrashed(),
                'course'  => fn($q) => $q->withTrashed(),
                'teacher',
            ])
            ->get();

        $teachers = Teacher::where('is_active', true)->orderBy('full_name')->get();
        $rooms = Room::where('is_active', true)->orderBy('name')->get();

        $preselectedEnrollment = $request->filled('enrollment_id') ? Enrollment::with('course')->find($request->enrollment_id) : null;

        return view('schedules.create', compact('enrollments', 'teachers', 'rooms', 'preselectedEnrollment'));
    }

    // POST /schedules
    public function store(StoreClassScheduleRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->user()->name ?? 'แอดมิน';

        ClassSchedule::create($data);

        return redirect()->route('schedules.index')->with('success', 'เพิ่มตารางเรียนเรียบร้อยแล้ว');
    }

    // GET /schedules/{classSchedule}/edit
    public function edit(ClassSchedule $classSchedule)
    {
        $classSchedule->load([
            'enrollment' => fn($q) => $q->with([
                'student' => fn($q2) => $q2->withTrashed(),
                'course'  => fn($q2) => $q2->withTrashed(),
            ]),
        ]);

        $enrollments = Enrollment::where('status', 'active')
            ->with([
                'student' => fn($q) => $q->withTrashed(),
                'course'  => fn($q) => $q->withTrashed(),
                'teacher',
            ])
            ->get();
        $teachers = Teacher::where('is_active', true)->orderBy('full_name')->get();
        $rooms = Room::where('is_active', true)->orderBy('name')->get();

        return view('schedules.edit', compact('classSchedule', 'enrollments', 'teachers', 'rooms'));
    }

    // PUT /schedules/{classSchedule}
    public function update(StoreClassScheduleRequest $request, ClassSchedule $classSchedule)
    {
        $classSchedule->update($request->validated());

        return redirect()->route('schedules.index')->with('success', 'แก้ไขตารางเรียนเรียบร้อยแล้ว');
    }

    // PATCH /schedules/{classSchedule}/cancel
    public function cancel(ClassSchedule $classSchedule)
    {
        $classSchedule->update(['status' => 'cancelled']);

        return back()->with('success', 'ยกเลิกตารางเรียนคาบนี้เรียบร้อยแล้ว');
    }

    // DELETE /schedules/{classSchedule}
    public function destroy(ClassSchedule $classSchedule)
    {
        $classSchedule->delete();

        return back()->with('success', 'ลบตารางเรียนเรียบร้อยแล้ว');
    }

    // GET /schedules/check-conflict — ตรวจสอบตารางซ้ำ + จำนวนครั้งคงเหลือ แบบ real-time
    public function checkConflict(Request $request)
    {
        $data = $request->validate([
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'teacher_id'    => ['nullable'],
            'room_id'       => ['nullable'],
            'schedule_date' => ['required', 'date'],
            'start_time'    => ['required'],
            'end_time'      => ['required'],
            'exclude_id'    => ['nullable'],
        ]);

        $enrollment = Enrollment::with('course')->find($data['enrollment_id']);

        $conflicts = ClassSchedule::findConflicts(
            $data['schedule_date'],
            $data['start_time'],
            $data['end_time'],
            $enrollment?->student_id,
            $data['teacher_id'] ?? null,
            $data['room_id'] ?? null,
            $data['exclude_id'] ?? null
        );

        // จำนวนครั้งที่จัดตารางไปแล้ว เทียบกับที่ซื้อไว้ในแพ็กเกจ
        $sessionInfo = null;
        if ($enrollment && $enrollment->course && $enrollment->course->total_sessions) {
            $usedCount = ClassSchedule::where('enrollment_id', $enrollment->id)
                ->whereIn('status', ['scheduled', 'completed'])
                ->when($data['exclude_id'] ?? null, fn($q, $id) => $q->where('id', '!=', $id))
                ->count();

            $sessionInfo = [
                'used'      => $usedCount,
                'total'     => $enrollment->course->total_sessions,
                'remaining' => max(0, $enrollment->course->total_sessions - $usedCount),
                'is_full'   => $usedCount >= $enrollment->course->total_sessions,
            ];
        }

        return response()->json(['conflicts' => $conflicts, 'session_info' => $sessionInfo]);
    }
}
