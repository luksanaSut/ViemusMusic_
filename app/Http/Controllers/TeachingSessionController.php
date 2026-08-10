<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherRate;
use App\Models\TeachingSession;
use Illuminate\Http\Request;

class TeachingSessionController extends Controller
{
    // เพิ่มประวัติการสอน / นัดสอน (ใช้ในหน้าโปรไฟล์อาจารย์)
    public function store(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'instrument_id'     => ['nullable', 'exists:instruments,id'],
            'teaching_type_id'  => ['nullable', 'exists:teaching_types,id'],
            'level_id'          => ['nullable', 'exists:levels,id'],
            'student_name'      => ['nullable', 'string', 'max:150'],
            'session_date'      => ['required', 'date'],
            'start_time'        => ['required'],
            'end_time'          => ['required', 'after:start_time'],
            'status'            => ['required', 'in:scheduled,completed,cancelled,no_show'],
            'note'              => ['nullable', 'string'],
        ]);

        // ดึงเรทที่ตรงกับ ประเภทการสอน/เครื่องดนตรี ที่ระบุ (ถ้าไม่พบ ใช้เรทค่าเริ่มต้นของอาจารย์)
        $rate = TeacherRate::where('teacher_id', $teacher->id)
            ->where('is_active', true)
            ->when($data['teaching_type_id'] ?? null, fn ($q, $v) => $q->where(function ($qq) use ($v) {
                $qq->where('teaching_type_id', $v)->orWhereNull('teaching_type_id');
            }))
            ->when($data['instrument_id'] ?? null, fn ($q, $v) => $q->where(function ($qq) use ($v) {
                $qq->where('instrument_id', $v)->orWhereNull('instrument_id');
            }))
            ->orderByRaw('teaching_type_id IS NULL, instrument_id IS NULL')
            ->first();

        $transportFee = $teacher->activeTransportFee;

        $data['teacher_id']            = $teacher->id;
        $data['rate_applied']          = $rate->rate_amount ?? 0;
        $data['transport_fee_applied'] = $transportFee->fee_amount ?? 0;

        TeachingSession::create($data);

        return back()->with('success', 'บันทึกประวัติการสอนเรียบร้อยแล้ว');
    }

    public function update(Request $request, Teacher $teacher, TeachingSession $session)
    {
        abort_if($session->teacher_id !== $teacher->id, 404);

        $data = $request->validate([
            'session_date' => ['required', 'date'],
            'start_time'   => ['required'],
            'end_time'     => ['required', 'after:start_time'],
            'status'       => ['required', 'in:scheduled,completed,cancelled,no_show'],
            'note'         => ['nullable', 'string'],
        ]);

        $session->update($data);

        return back()->with('success', 'แก้ไขประวัติการสอนเรียบร้อยแล้ว');
    }

    public function destroy(Teacher $teacher, TeachingSession $session)
    {
        abort_if($session->teacher_id !== $teacher->id, 404);
        $session->delete();

        return back()->with('success', 'ลบประวัติการสอนเรียบร้อยแล้ว');
    }
}
