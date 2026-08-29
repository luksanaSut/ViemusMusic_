<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Teacher;
use App\Models\TeacherLeave;
use App\Models\TeacherLeaveAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TeacherLeaveController extends Controller
{
    // GET /teacher-leaves — ประวัติคำขอลาของอาจารย์ทั้งหมด
    public function index(Request $request)
    {
        $leaves = TeacherLeave::with(['teacher', 'attachments'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->when($request->filled('q'), fn($q) => $q->whereHas('teacher', fn($qq) => $qq->where('full_name', 'like', '%' . $request->q . '%')))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('teacher-leaves.index', compact('leaves'));
    }

    // GET /my-teacher-leave — หน้าแจ้งลาหยุดสอนของอาจารย์เอง (ใช้ได้เฉพาะบัญชี role teacher)
    public function myIndex(Request $request)
    {
        $teacher = $request->user()->teacher;
        abort_unless($teacher, 404, 'บัญชีนี้ยังไม่ได้ผูกกับข้อมูลอาจารย์');

        $leaves = $teacher->leaves()->with('attachments')->orderByDesc('created_at')->get();

        return view('teacher-leaves.my-index', compact('teacher', 'leaves'));
    }

    // POST /teachers/{teacher}/leaves — อาจารย์แจ้งลาหยุดสอน
    public function store(Request $request, Teacher $teacher)
    {
        if ($request->user()->isTeacher() && $request->user()->teacher_id !== $teacher->id) {
            abort(403, 'คุณสามารถแจ้งลาหยุดสอนได้เฉพาะบัญชีของตัวเองเท่านั้น');
        }

        $data = $request->validate([
            'leave_date_from' => ['required', 'date'],
            'leave_date_to'   => ['required', 'date', 'after_or_equal:leave_date_from'],
            'reason'          => ['nullable', 'string', 'max:500'],
            'attachments'     => ['nullable', 'array', 'max:5'],
            'attachments.*'   => ['file', 'max:10240', 'mimes:jpg,jpeg,png,webp,pdf,doc,docx'],
        ]);

        $data['teacher_id'] = $teacher->id;
        $data['status'] = 'pending';
        $data['created_by'] = auth()->user()->name ?? 'แอดมิน';

        $leave = DB::transaction(function () use ($request, $data) {
            $leave = TeacherLeave::create(collect($data)->except('attachments')->all());
            foreach ($request->file('attachments', []) as $file) {
                $path = $file->store('teacher-leaves', 'local');
                $leave->attachments()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
            return $leave;
        });
        $affectedCount = $leave->affectedSchedules()->count();

        AppNotification::notifyAdmins(
            'อาจารย์แจ้งลาหยุดสอน',
            "{$teacher->full_name} แจ้งลาวันที่ {$leave->leave_date_from->format('d/m/Y')} - {$leave->leave_date_to->format('d/m/Y')}" .
                ($affectedCount > 0 ? " (กระทบตารางสอน {$affectedCount} คาบ)" : ''),
            route('teacher-leaves.index')
        );

        return back()->with('success', 'ส่งคำขอลาหยุดสอนเรียบร้อยแล้ว รอการอนุมัติ');
    }

    public function downloadAttachment(Request $request, TeacherLeaveAttachment $attachment)
    {
        $this->authorizeAttachment($request, $attachment);
        abort_unless(Storage::disk('local')->exists($attachment->file_path), 404);
        return Storage::disk('local')->download($attachment->file_path, $attachment->original_name);
    }

    public function destroyAttachment(Request $request, TeacherLeaveAttachment $attachment)
    {
        $this->authorizeAttachment($request, $attachment);
        abort_if($attachment->teacherLeave->status !== 'pending', 422, 'ลบไฟล์ได้เฉพาะคำขอที่ยังรออนุมัติ');
        Storage::disk('local')->delete($attachment->file_path);
        $attachment->delete();
        return back()->with('success', 'ลบไฟล์แนบแล้ว');
    }

    private function authorizeAttachment(Request $request, TeacherLeaveAttachment $attachment): void
    {
        $user = $request->user();
        $canManage = $user->isAdmin() || ($user->isStaff() && $user->hasModulePermission('teachers.manage'));
        abort_unless($canManage || $user->teacher_id === $attachment->teacherLeave->teacher_id, 403);
    }

    // POST /teacher-leaves/{teacherLeave}/approve
    public function approve(Request $request, TeacherLeave $teacherLeave)
    {
        if ($teacherLeave->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกดำเนินการไปแล้ว');
        }

        $teacherLeave->update(['status' => 'approved', 'reviewed_by' => auth()->user()->name ?? 'แอดมิน', 'reviewed_at' => now()]);

        // เลือกได้ว่าจะยกเลิกคาบสอนที่ได้รับผลกระทบทั้งหมดพร้อมกันเลยหรือไม่
        if ($request->boolean('cancel_affected')) {
            $teacherLeave->affectedSchedules()->each(fn($s) => $s->update(['status' => 'cancelled']));
        }

        return back()->with('success', 'อนุมัติคำขอลาหยุดสอนเรียบร้อยแล้ว');
    }

    // POST /teacher-leaves/{teacherLeave}/reject
    public function reject(TeacherLeave $teacherLeave)
    {
        if ($teacherLeave->status !== 'pending') {
            return back()->with('error', 'คำขอนี้ถูกดำเนินการไปแล้ว');
        }

        $teacherLeave->update(['status' => 'rejected', 'reviewed_by' => auth()->user()->name ?? 'แอดมิน', 'reviewed_at' => now()]);

        return back()->with('success', 'ปฏิเสธคำขอลาหยุดสอนแล้ว');
    }
}
