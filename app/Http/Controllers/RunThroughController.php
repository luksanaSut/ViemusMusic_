<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\ClassSchedule;
use App\Models\Enrollment;
use App\Models\RunThrough;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RunThroughController extends Controller
{
    // GET /run-throughs — ประวัติทั้งหมด (Admin/Teacher)
    public function index(Request $request)
    {
        $user = $request->user();

        $runThroughs = RunThrough::with(['enrollment.student', 'enrollment.course', 'teacher', 'attachments'])
            ->when($user->isTeacher() && $user->teacher_id, fn($q) => $q->where('teacher_id', $user->teacher_id))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('run-throughs.index', compact('runThroughs'));
    }

    // GET /enrollments/{enrollment}/run-throughs/create
    public function create(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id && $enrollment->teacher_id !== $user->teacher_id) {
            abort(403);
        }
        $enrollment->load('student', 'course');

        return view('run-throughs.create', compact('enrollment'));
    }

    // POST /enrollments/{enrollment}/run-throughs — สร้างแบบฝึกหัดทบทวน
    public function store(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id && $enrollment->teacher_id !== $user->teacher_id) {
            abort(403);
        }

        $teacherId = $user->isTeacher() && $user->teacher_id ? $user->teacher_id : $enrollment->teacher_id;
        if (!$teacherId) {
            $teacherId = ClassSchedule::where('enrollment_id', $enrollment->id)
                ->whereNotNull('teacher_id')
                ->latest('schedule_date')
                ->value('teacher_id');
        }
        if (!$teacherId) {
            return back()->withInput()->with('error', 'ไม่สามารถสร้างแบบฝึกหัดได้ เนื่องจากยังไม่มีอาจารย์ผู้สอนของนักเรียนคนนี้ กรุณาจัดตารางเรียนหรือระบุอาจารย์ในการลงทะเบียนก่อน');
        }

        $data = $request->validate([
            'title'        => ['required', 'string', 'max:200'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'attachments'   => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'max:20480', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx,mscz,xml'],
        ]);

        $runThrough = null;
        DB::transaction(function () use ($request, $data, $enrollment, $teacherId, $user, &$runThrough) {
            $runThrough = RunThrough::create([
                'enrollment_id' => $enrollment->id,
                'teacher_id'    => $teacherId,
                'title'          => $data['title'],
                'description'    => $data['description'] ?? null,
                'created_by'     => $user->displayName(),
            ]);

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('run-through-attachments', 'public');
                    $runThrough->attachments()->create([
                        'file_path'     => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        AppNotification::notifyStudentAndGuardians(
            $enrollment->student,
            'มีแบบฝึกหัดทบทวนใหม่ (Run Through)',
            "{$runThrough->title} — {$enrollment->course->name}",
            route('run-throughs.my-index')
        );

        return redirect()->route('run-throughs.index')->with('success', 'สร้างแบบฝึกหัดทบทวนเรียบร้อยแล้ว');
    }

    // POST /run-throughs/{runThrough}/record-result — บันทึกผลการฝึกซ้อม
    public function recordResult(Request $request, RunThrough $runThrough)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id !== $runThrough->teacher_id) {
            abort(403);
        }

        $data = $request->validate([
            'practice_result'   => ['required', 'in:excellent,good,needs_practice'],
            'areas_to_improve'   => ['nullable', 'string', 'max:1500'],
            'teacher_comment'    => ['nullable', 'string', 'max:1500'],
        ]);

        $runThrough->update([
            'practice_result'     => $data['practice_result'],
            'areas_to_improve'     => $data['areas_to_improve'] ?? null,
            'teacher_comment'      => $data['teacher_comment'] ?? null,
            'result_recorded_at'   => now(),
        ]);

        AppNotification::notifyStudentAndGuardians(
            $runThrough->enrollment->student,
            'บันทึกผลการฝึกซ้อม Run Through แล้ว',
            "{$runThrough->title} — ผล: {$runThrough->practiceResultLabel()}",
            route('run-throughs.my-index')
        );

        return back()->with('success', 'บันทึกผลการฝึกซ้อมเรียบร้อยแล้ว');
    }

    // GET /my-run-throughs — นักเรียน/ผู้ปกครองดูประวัติ
    public function myIndex(Request $request)
    {
        $user = $request->user();
        $students = collect();

        if ($user->isStudent() && $user->student) {
            $students = collect([$user->student]);
        } elseif ($user->isGuardian() && $user->guardian) {
            $students = $user->guardian->students;
        }

        $studentIds = $students->pluck('id');

        $runThroughs = RunThrough::with(['enrollment.student', 'enrollment.course', 'teacher', 'attachments'])
            ->whereHas('enrollment', fn($q) => $q->whereIn('student_id', $studentIds))
            ->orderByDesc('created_at')
            ->get();

        return view('run-throughs.my-index', compact('runThroughs'));
    }
}