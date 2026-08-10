<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\TeacherRate;
use App\Models\TeacherTransportFee;
use Illuminate\Http\Request;

class TeacherRateController extends Controller
{
    // เพิ่ม/แก้ไข เรทค่าจ้าง (รองรับกำหนดแยกตามอาจารย์ / ประเภทการสอน / เครื่องดนตรี)
    public function store(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'teaching_type_id' => ['nullable', 'exists:teaching_types,id'],
            'instrument_id'    => ['nullable', 'exists:instruments,id'],
            'rate_type'        => ['required', 'in:per_hour,per_session,monthly_fixed'],
            'rate_amount'      => ['required', 'numeric', 'min:0'],
            'effective_from'   => ['nullable', 'date'],
            'note'             => ['nullable', 'string'],
        ]);

        $data['teacher_id'] = $teacher->id;
        $data['effective_from'] = $data['effective_from'] ?? now()->toDateString();
        $data['is_active'] = true;

        TeacherRate::create($data);

        return back()->with('success', 'เพิ่มเรทค่าจ้างเรียบร้อยแล้ว');
    }

    public function destroy(Teacher $teacher, TeacherRate $rate)
    {
        abort_if($rate->teacher_id !== $teacher->id, 404);
        $rate->update(['is_active' => false, 'effective_to' => now()->toDateString()]);

        return back()->with('success', 'ปิดการใช้งานเรทค่าจ้างนี้แล้ว');
    }

    // ค่ารถ
    public function storeTransportFee(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'fee_type'   => ['required', 'in:fixed_per_day,per_km'],
            'fee_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $teacher->transportFees()->update(['is_active' => false]);

        $teacher->transportFees()->create([
            ...$data,
            'effective_from' => now()->toDateString(),
            'is_active'      => true,
        ]);

        return back()->with('success', 'ตั้งค่าค่ารถเรียบร้อยแล้ว');
    }
}
