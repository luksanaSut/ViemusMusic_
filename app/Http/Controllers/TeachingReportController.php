<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\TeachingLog;
use App\Models\TeachingReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeachingReportController extends Controller
{
    // POST /teaching-logs/{teachingLog}/report — บันทึกผลการสอนหลังเช็คชื่อ (Admin/Teacher เจ้าของคาบ)
    public function store(Request $request, TeachingLog $teachingLog)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id !== $teachingLog->teacher_id) {
            abort(403, 'คุณสามารถบันทึกผลการสอนได้เฉพาะคาบสอนของตัวเองเท่านั้น');
        }
        if (!$teachingLog->attendance_status) {
            return back()->with('error', 'กรุณาเช็คชื่อก่อนบันทึกผลการสอน');
        }

        $data = $request->validate([
            'content_taught' => ['required', 'string', 'max:3000'],
            'homework'        => ['nullable', 'string', 'max:2000'],
            'progress_notes'  => ['required', 'string', 'max:2000'],
            'notes'           => ['required', 'string', 'max:1000'],
            'attachments'     => ['nullable', 'array', 'max:5'],
            'attachments.*'   => ['file', 'mimes:pdf,jpg,jpeg,png,mp3,mp4,mscz,xml,doc,docx', 'max:10240'],
        ]);

        DB::transaction(function () use ($request, $data, $teachingLog, $user) {
            $report = TeachingReport::updateOrCreate(
                ['teaching_log_id' => $teachingLog->id],
                [
                    'content_taught' => $data['content_taught'] ?? null,
                    'homework'        => $data['homework'] ?? null,
                    'progress_notes'  => $data['progress_notes'] ?? null,
                    'notes'           => $data['notes'] ?? null,
                    'created_by'      => $user->displayName(),
                ]
            );

            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('teaching-report-attachments', 'public');
                    $report->attachments()->create([
                        'file_path'     => $path,
                        'original_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        });

        AppNotification::notifyStudentAndGuardians(
            $teachingLog->student,
            'มีผลการสอนใหม่',
            "อาจารย์บันทึกผลการสอนคาบวันที่ {$teachingLog->classSchedule->schedule_date->format('d/m/Y')} แล้ว",
            route('teaching-reports.my-index')
        );

        return back()->with('success', 'บันทึกผลการสอนเรียบร้อยแล้ว');
    }

    // DELETE /teaching-report-attachments/{attachment} — ลบไฟล์แนบ (แก้ไขก่อนจบคาบ)
    public function destroyAttachment(Request $request, \App\Models\TeachingReportAttachment $attachment)
    {
        $user = $request->user();
        $teachingLog = $attachment->teachingReport->teachingLog;
        if ($user->isTeacher() && $user->teacher_id !== $teachingLog->teacher_id) {
            abort(403);
        }

        \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return back()->with('success', 'ลบไฟล์แนบแล้ว');
    }

    // ===== หน้าสำหรับนักเรียน/ผู้ปกครอง: ดูผลการสอนย้อนหลัง (read-only) =====
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

        $reports = TeachingReport::with([
            'teachingLog.classSchedule',
            'teachingLog.student',
            'teachingLog.enrollment.course',
            'teachingLog.evidences',
            'attachments',
        ])
            ->whereHas('teachingLog', fn($q) => $q->whereIn('student_id', $studentIds))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('teaching-reports.my-index', compact('reports', 'students'));
    }
}
