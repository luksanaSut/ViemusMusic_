<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\HomeworkSubmission;
use App\Models\TeachingReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeworkSubmissionController extends Controller
{
    // POST /teaching-reports/{teachingReport}/homework-submissions — นักเรียน/ผู้ปกครองส่งการบ้าน
    public function store(Request $request, TeachingReport $teachingReport)
    {
        $studentId = $teachingReport->teachingLog->student_id;
        $this->authorizeStudentOrGuardian($request, $studentId);

        if (!$teachingReport->homework) {
            return back()->with('error', 'คาบนี้ไม่มีการบ้านที่ต้องส่ง');
        }

        $data = $request->validate([
            'student_note' => ['nullable', 'string', 'max:1000'],
            'files'         => ['required', 'array', 'min:1', 'max:10'],
            'files.*'       => ['file', 'max:51200', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx,mp3,mp4,mov,m4a'],
        ], [
            'files.*.mimes' => 'รองรับเฉพาะไฟล์รูปภาพ, PDF, เอกสาร, เสียง หรือวิดีโอเท่านั้น',
        ]);

        // Business rule: ส่งใหม่กรณีแก้ไขงาน -> version เพิ่มขึ้นทุกครั้ง ไม่ทับของเดิม
        $nextVersion = ($teachingReport->latestHomeworkSubmission()?->version ?? 0) + 1;

        $submission = null;
        DB::transaction(function () use ($request, $data, $teachingReport, $studentId, $nextVersion, &$submission) {
            $submission = HomeworkSubmission::create([
                'teaching_report_id' => $teachingReport->id,
                'student_id'          => $studentId,
                'version'             => $nextVersion,
                'student_note'        => $data['student_note'] ?? null,
                'status'              => 'submitted',
                'submitted_by'        => $request->user()->displayName(),
            ]);

            foreach ($request->file('files') as $file) {
                $path = $file->store('homework-submissions', 'public');
                $submission->files()->create([
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        });

        $teacherId = $teachingReport->teachingLog->teacher_id;
        if ($teacherId) {
            AppNotification::notifyTeacher(
                $teacherId,
                $nextVersion > 1 ? 'นักเรียนส่งการบ้านแก้ไขใหม่' : 'นักเรียนส่งการบ้านแล้ว',
                "{$submission->student->full_name} ส่งการบ้าน (ครั้งที่ {$nextVersion})",
                route('homework-submissions.index')
            );
        }

        return back()->with('success', 'ส่งการบ้านเรียบร้อยแล้ว รออาจารย์ตรวจ');
    }

    // GET /homework-submissions — รายการรอตรวจ + ประวัติ (Admin/Teacher)
    public function index(Request $request)
    {
        $user = $request->user();

        $submissions = HomeworkSubmission::with(['student', 'teachingReport.teachingLog.enrollment.course', 'files'])
            ->when($user->isTeacher() && $user->teacher_id, fn($q) => $q->whereHas('teachingReport.teachingLog', fn($qq) => $qq->where('teacher_id', $user->teacher_id)))
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->orderByRaw("status = 'submitted' desc")
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('homework-submissions.index', compact('submissions'));
    }

    // POST /homework-submissions/{homeworkSubmission}/review — อาจารย์ตรวจและให้ Feedback
    public function review(Request $request, HomeworkSubmission $homeworkSubmission)
    {
        $user = $request->user();
        $teacherId = $homeworkSubmission->teachingReport->teachingLog->teacher_id;
        if ($user->isTeacher() && $user->teacher_id !== $teacherId) {
            abort(403, 'คุณสามารถตรวจการบ้านได้เฉพาะคาบสอนของตัวเองเท่านั้น');
        }

        $data = $request->validate([
            'status'   => ['required', 'in:approved,needs_revision'],
            'feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        $homeworkSubmission->update([
            'status'      => $data['status'],
            'feedback'    => $data['feedback'] ?? null,
            'reviewed_by' => $user->displayName(),
            'reviewed_at' => now(),
        ]);

        // Feature: แจ้งเตือนเมื่อการบ้านถูกตรวจแล้ว
        AppNotification::notifyStudentAndGuardians(
            $homeworkSubmission->student,
            $data['status'] === 'approved' ? 'การบ้านผ่านแล้ว!' : 'การบ้านต้องแก้ไข',
            $data['status'] === 'approved'
                ? 'อาจารย์ตรวจการบ้านของคุณผ่านแล้ว'
                : 'อาจารย์ให้ feedback การบ้านของคุณ กรุณาแก้ไขและส่งใหม่',
            route('homework-submissions.my-index')
        );

        return back()->with('success', 'บันทึกผลการตรวจการบ้านเรียบร้อยแล้ว');
    }

    // GET /my-homework — นักเรียน/ผู้ปกครองดูประวัติการบ้านย้อนหลัง
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

        $submissions = HomeworkSubmission::with(['teachingReport.teachingLog.enrollment.course', 'files'])
            ->whereIn('student_id', $studentIds)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('homework-submissions.my-index', compact('submissions'));
    }

    private function authorizeStudentOrGuardian(Request $request, int $studentId): void
    {
        $user = $request->user();
        if ($user->isAdmin()) return;
        if ($user->isStudent() && $user->student_id === $studentId) return;
        if ($user->isGuardian() && $user->guardian?->students->pluck('id')->contains($studentId)) return;
        abort(403, 'คุณสามารถส่งการบ้านให้ตัวเองหรือบุตรหลานที่ผูกกับบัญชีของคุณเท่านั้น');
    }
}