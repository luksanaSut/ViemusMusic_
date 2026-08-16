<?php

namespace App\Http\Controllers;

use App\Models\TeachingEvidence;
use App\Models\TeachingLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeachingEvidenceController extends Controller
{
    // กำหนด mime type ที่อนุญาตแยกตามประเภท (Validation rule: รองรับรูปภาพ วิดีโอ PDF และไฟล์เอกสาร)
    private const ALLOWED_MIMES = [
        'image'    => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
        'video'    => ['video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/webm'],
        'document' => [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ],
    ];

    // POST /teaching-logs/{teachingLog}/evidences — อัปโหลดหลักฐานการสอน
    public function store(Request $request, TeachingLog $teachingLog)
    {
        $user = $request->user();
        if ($user->isTeacher() && $user->teacher_id !== $teachingLog->teacher_id) {
            abort(403, 'คุณสามารถอัปโหลดหลักฐานได้เฉพาะคาบสอนของตัวเองเท่านั้น');
        }

        $data = $request->validate([
            'files'   => ['required', 'array', 'max:10'],
            'files.*' => [
                'file',
                'max:51200', // สูงสุด 50MB ต่อไฟล์ (วิดีโอไฟล์ใหญ่)
                'mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm,pdf,doc,docx,xls,xlsx',
            ],
        ], [
            'files.*.mimes' => 'รองรับเฉพาะไฟล์รูปภาพ, วิดีโอ, PDF, และเอกสาร (Word/Excel) เท่านั้น',
            'files.*.max'   => 'ไฟล์ต้องมีขนาดไม่เกิน 50MB',
        ]);

        $uploaded = 0;
        foreach ($request->file('files') as $file) {
            $fileType = $this->detectFileType($file->getMimeType());
            if (!$fileType) continue; // กันไฟล์ที่ผ่าน extension check มาแต่ mime จริงไม่ตรง

            $path = $file->store('teaching-evidences/' . $fileType, 'public');

            TeachingEvidence::create([
                'teaching_log_id'      => $teachingLog->id,
                'file_type'            => $fileType,
                'file_path'            => $path,
                'original_name'        => $file->getClientOriginalName(),
                'mime_type'            => $file->getMimeType(),
                'file_size'            => $file->getSize(),
                'uploaded_by_user_id'  => $user->id,
                'uploaded_by_name'     => $user->displayName(),
            ]);
            $uploaded++;
        }

        return back()->with('success', "อัปโหลดหลักฐานการสอนสำเร็จ {$uploaded} ไฟล์");
    }

    // GET /teaching-evidences/{teachingEvidence}/download — ดาวน์โหลดผ่านระบบ (บันทึกสิทธิ์เข้าถึงตรงนี้ได้ในอนาคต)
    public function download(Request $request, TeachingEvidence $teachingEvidence)
    {
        $user = $request->user();
        $teachingLog = $teachingEvidence->teachingLog;

        // สิทธิ์เข้าถึง: Admin ทุกไฟล์ / Teacher เฉพาะคาบตัวเอง / Student-Guardian เฉพาะของตัวเอง-บุตรหลาน
        if ($user->isTeacher() && $user->teacher_id !== $teachingLog->teacher_id) abort(403);
        if ($user->isStudent() && $user->student_id !== $teachingLog->student_id) abort(403);
        if ($user->isGuardian()) {
            $allowed = $user->guardian?->students->pluck('id')->contains($teachingLog->student_id);
            abort_unless($allowed, 403);
        }

        abort_unless(Storage::disk('public')->exists($teachingEvidence->file_path), 404);

        return Storage::disk('public')->download($teachingEvidence->file_path, $teachingEvidence->original_name);
    }

    // DELETE /teaching-evidences/{teachingEvidence}
    public function destroy(Request $request, TeachingEvidence $teachingEvidence)
    {
        $user = $request->user();
        $teachingLog = $teachingEvidence->teachingLog;
        if ($user->isTeacher() && $user->teacher_id !== $teachingLog->teacher_id) abort(403);

        Storage::disk('public')->delete($teachingEvidence->file_path);
        $teachingEvidence->delete();

        return back()->with('success', 'ลบหลักฐานการสอนแล้ว');
    }

    private function detectFileType(?string $mimeType): ?string
    {
        foreach (self::ALLOWED_MIMES as $type => $mimes) {
            if (in_array($mimeType, $mimes)) return $type;
        }
        return null;
    }
}