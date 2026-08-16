<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\CourseEvaluation;
use App\Models\Enrollment;
use App\Models\EvaluationCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseEvaluationController extends Controller
{
    // GET /enrollments/{enrollment}/evaluation/create-or-edit — ฟอร์มประเมินผลจบคอร์ส
    public function edit(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id && $enrollment->teacher_id !== $user->teacher_id) {
            abort(403, 'คุณสามารถประเมินผลได้เฉพาะนักเรียนที่คุณสอนเท่านั้น');
        }

        $enrollment->load(['student', 'course']);
        $categories = EvaluationCategory::where('is_active', true)->orderBy('sort_order')->get();
        $evaluation = CourseEvaluation::with('items')->firstOrNew(['enrollment_id' => $enrollment->id]);

        return view('course-evaluations.edit', compact('enrollment', 'categories', 'evaluation'));
    }

    // POST /enrollments/{enrollment}/evaluation
    public function store(Request $request, Enrollment $enrollment)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id && $enrollment->teacher_id !== $user->teacher_id) {
            abort(403);
        }

        $data = $request->validate([
            'overall_comment'  => ['nullable', 'string', 'max:2000'],
            'status'           => ['required', 'in:draft,published'],
            'items'            => ['required', 'array', 'min:1'],
            'items.*.category_id' => ['required', 'exists:evaluation_categories,id'],
            'items.*.score'        => ['required', 'integer', 'min:1', 'max:5'],
            'items.*.comment'      => ['nullable', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($data, $enrollment, $user) {
            $evaluation = CourseEvaluation::updateOrCreate(
                ['enrollment_id' => $enrollment->id],
                [
                    'overall_comment' => $data['overall_comment'] ?? null,
                    'status'          => $data['status'],
                    'evaluated_by'    => $user->displayName(),
                    'evaluated_at'    => $data['status'] === 'published' ? now() : null,
                ]
            );

            $evaluation->items()->delete();
            foreach ($data['items'] as $item) {
                $evaluation->items()->create([
                    'evaluation_category_id' => $item['category_id'],
                    'score'                   => $item['score'],
                    'comment'                 => $item['comment'] ?? null,
                ]);
            }
        });

        if ($data['status'] === 'published') {
            AppNotification::notifyStudentAndGuardians(
                $enrollment->student,
                'มีผลประเมินจบคอร์สใหม่',
                "ผลประเมินจบคอร์ส {$enrollment->course->name} พร้อมให้ดูแล้ว",
                route('course-evaluations.my-index')
            );
        }

        return redirect()->route('students.show', $enrollment->student)->with(
            'success',
            $data['status'] === 'published' ? 'บันทึกและเผยแพร่ผลประเมินเรียบร้อยแล้ว' : 'บันทึกฉบับร่างเรียบร้อยแล้ว'
        );
    }

    // ===== หน้าสำหรับนักเรียน/ผู้ปกครอง: ดูผลประเมินจบคอร์ส (เฉพาะที่เผยแพร่แล้ว) =====
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

        $evaluations = CourseEvaluation::with(['enrollment.student', 'enrollment.course', 'items.category'])
            ->where('status', 'published')
            ->whereHas('enrollment', fn($q) => $q->whereIn('student_id', $studentIds))
            ->orderByDesc('evaluated_at')
            ->get();

        return view('course-evaluations.my-index', compact('evaluations'));
    }
}