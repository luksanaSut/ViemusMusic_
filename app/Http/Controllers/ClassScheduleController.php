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
use Illuminate\Support\Facades\DB;

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

    // GET /schedules/bulk-create — หน้าจัดตารางแบบชุด (wizard 2 ขั้นตอน)
    public function bulkCreate(Request $request)
    {
        $enrollments = Enrollment::where('status', 'active')
            ->with([
                'student' => fn($q) => $q->withTrashed(),
                'course'  => fn($q) => $q->withTrashed()->with('teachers'),
                'teacher',
            ])
            ->get();

        $teachers = Teacher::where('is_active', true)->orderBy('full_name')->get();
        $rooms = Room::where('is_active', true)->orderBy('name')->get();

        $preselectedEnrollment = $request->filled('enrollment_id') ? Enrollment::with('course')->find($request->enrollment_id) : null;

        return view('schedules.bulk-create', compact('enrollments', 'teachers', 'rooms', 'preselectedEnrollment'));
    }

    // POST /schedules/bulk-preview — สร้างรายการ "ร่าง" ยังไม่บันทึกจริง ส่งกลับให้แก้ไขทีละแถวก่อน
    public function bulkPreview(Request $request)
    {
        $data = $request->validate([
            'enrollment_id'   => ['required', 'exists:enrollments,id'],
            'teacher_id'      => ['nullable', 'exists:teachers,id'],
            'room_id'         => ['nullable', 'exists:rooms,id'],
            'delivery_mode'   => ['required', 'in:onsite,online,hybrid'],
            'mode'            => ['required', 'in:weekly,daily_range'],

            'days_of_week'    => ['nullable', 'array'],
            'days_of_week.*'  => ['integer', 'between:0,6'],
            'start_date'      => ['nullable', 'required_if:mode,weekly', 'date'],
            'session_count'   => ['nullable', 'required_if:mode,weekly', 'integer', 'min:1', 'max:200'],

            'start_time'      => ['required'],
            'end_time'        => ['required', 'after:start_time'],
        ]);

        $enrollment = Enrollment::with('course')->findOrFail($data['enrollment_id']);
        $course = $enrollment->course;
        $rows = [];

        if ($data['mode'] === 'daily_range') {
            if (!$course || $course->structure_type !== 'special' || !$course->course_start_date || !$course->course_end_date) {
                return response()->json(['error' => 'คอร์สนี้ไม่ใช่คอร์สแบบพิเศษ หรือยังไม่ได้กำหนดวันที่คอร์สไว้'], 422);
            }

            $date = $course->course_start_date->copy();
            $endDate = $course->course_end_date->copy();

            while ($date->lte($endDate)) {
                $rows[] = $this->buildPreviewRow($date->toDateString(), $data, $enrollment);
                $date->addDay();
            }
        } else {
            $daysOfWeek = $data['days_of_week'];
            $date = Carbon::parse($data['start_date']);
            $attempts = 0;
            $maxAttempts = ($data['session_count'] * 14) + 60;

            while (count($rows) < $data['session_count'] && $attempts < $maxAttempts) {
                $attempts++;
                if (in_array($date->dayOfWeek, $daysOfWeek)) {
                    $rows[] = $this->buildPreviewRow($date->toDateString(), $data, $enrollment);
                }
                $date->addDay();
            }
        }

        return response()->json(['rows' => $rows]);
    }

    private function buildPreviewRow(string $date, array $data, Enrollment $enrollment): array
    {
        $teacherId = $data['teacher_id'] ?? null;
        $roomId = $data['room_id'] ?? null;

        $conflicts = ClassSchedule::findConflicts(
            $date,
            $data['start_time'],
            $data['end_time'],
            $enrollment->student_id,
            $teacherId,
            $roomId
        );

        return [
            'date'          => $date,
            'start_time'    => $data['start_time'],
            'end_time'      => $data['end_time'],
            'teacher_id'    => $teacherId,
            'room_id'       => $roomId,
            'delivery_mode' => $data['delivery_mode'],
            'conflicts'     => $conflicts,
        ];
    }

    // GET /schedules/bulk-row-check-conflict — เช็คตารางชนของแถวเดียวแบบ real-time (ใช้ตอนแก้ไขในตาราง preview)
    public function bulkRowCheckConflict(Request $request)
    {
        $data = $request->validate([
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'teacher_id'    => ['nullable'],
            'room_id'       => ['nullable'],
            'date'          => ['required', 'date'],
            'start_time'    => ['required'],
            'end_time'      => ['required'],
        ]);

        $enrollment = Enrollment::find($data['enrollment_id']);

        $conflicts = ClassSchedule::findConflicts(
            $data['date'],
            $data['start_time'],
            $data['end_time'],
            $enrollment->student_id,
            $data['teacher_id'] ?? null,
            $data['room_id'] ?? null
        );

        return response()->json(['conflicts' => $conflicts]);
    }

    // POST /schedules/bulk-confirm — บันทึกจริงจากรายการที่แก้ไขแล้วในหน้า preview
    public function bulkConfirm(Request $request)
    {
        $data = $request->validate([
            'enrollment_id' => ['required', 'exists:enrollments,id'],
            'rows'          => ['required', 'array', 'min:1'],
            'rows.*.date'          => ['required', 'date'],
            'rows.*.start_time'    => ['required'],
            'rows.*.end_time'      => ['required', 'after:rows.*.start_time'],
            'rows.*.teacher_id'    => ['nullable', 'exists:teachers,id'],
            'rows.*.room_id'       => ['nullable', 'exists:rooms,id'],
            'rows.*.delivery_mode' => ['required', 'in:onsite,online,hybrid'],
            'notes'         => ['nullable', 'string', 'max:1000'],
        ]);

        $enrollment = Enrollment::with('course')->findOrFail($data['enrollment_id']);
        $createdBy = auth()->user()->name ?? 'แอดมิน';

        // Business rule: ห้ามจัดตารางเกินจำนวนครั้งที่ซื้อไว้ในแพ็กเกจ
        if ($enrollment->course && $enrollment->course->total_sessions) {
            $usedCount = ClassSchedule::where('enrollment_id', $enrollment->id)
                ->whereIn('status', ['scheduled', 'completed'])->count();
            $remaining = $enrollment->course->total_sessions - $usedCount;
            if (count($data['rows']) > $remaining) {
                return back()->with('error', "คอร์สนี้เหลือสิทธิ์จัดตารางได้อีกแค่ {$remaining} ครั้ง (พยายามบันทึก " . count($data['rows']) . " ครั้ง)");
            }
        }

        $created = 0;
        $skipped = [];

        DB::transaction(function () use ($data, $enrollment, $createdBy, &$created, &$skipped) {
            foreach ($data['rows'] as $row) {
                $conflicts = ClassSchedule::findConflicts(
                    $row['date'],
                    $row['start_time'],
                    $row['end_time'],
                    $enrollment->student_id,
                    $row['teacher_id'] ?? null,
                    $row['room_id'] ?? null
                );

                if (!empty($conflicts)) {
                    $skipped[] = ['date' => \Carbon\Carbon::parse($row['date'])->format('d/m/Y'), 'reasons' => $conflicts];
                    continue;
                }

                ClassSchedule::create([
                    'enrollment_id' => $enrollment->id,
                    'teacher_id'    => $row['teacher_id'] ?? null,
                    'room_id'       => $row['room_id'] ?? null,
                    'schedule_date' => $row['date'],
                    'start_time'    => $row['start_time'],
                    'end_time'      => $row['end_time'],
                    'delivery_mode' => $row['delivery_mode'],
                    'status'        => 'scheduled',
                    'notes'         => $data['notes'] ?? null,
                    'created_by'    => $createdBy,
                ]);
                $created++;
            }
        });

        $message = "บันทึกตารางเรียนสำเร็จ {$created} คาบ";
        if (count($skipped) > 0) {
            $message .= ' (ข้าม ' . count($skipped) . ' คาบเพราะมีตารางชนกันตอนบันทึกจริง)';
        }

        return redirect()->route('schedules.index')->with('success', $message)->with('bulk_skipped', $skipped);
    }
}