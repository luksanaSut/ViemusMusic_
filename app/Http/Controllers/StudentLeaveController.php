<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentLeaveController extends Controller
{
    // POST /students/{student}/leaves
    // Business rule: ลาฉุกเฉินได้ตามโควตาที่คอร์สกำหนด (emergency_leave_quota) เท่านั้น
    public function store(Request $request, Student $student)
    {
        $data = $request->validate([
            'enrollment_id'       => ['required', 'exists:enrollments,id'],
            'leave_type'          => ['required', 'in:emergency,normal'],
            'leave_date'          => ['required', 'date'],
            'reason'              => ['nullable', 'string', 'max:500'],
            'is_makeup_required'  => ['nullable', 'boolean'],
        ]);

        $enrollment = Enrollment::findOrFail($data['enrollment_id']);
        abort_if($enrollment->student_id !== $student->id, 404);

        if ($data['leave_type'] === 'emergency' && $enrollment->emergencyLeaveRemaining() <= 0) {
            return back()->with(
                'error',
                "ใช้สิทธิ์ลาฉุกเฉินครบตามโควตาแล้ว ({$enrollment->emergencyLeaveQuota()} ครั้ง/คอร์ส ตามนโยบาย)"
            );
        }

        $data['student_id'] = $student->id;
        $data['is_makeup_required'] = $request->boolean('is_makeup_required', true);
        $data['makeup_status'] = $data['is_makeup_required'] ? 'pending' : 'not_required';

        $student->leaves()->create($data);

        return back()->with('success', 'บันทึกการลาเรียบร้อยแล้ว');
    }

    // PATCH /students/{student}/leaves/{leave}/makeup
    public function updateMakeup(Request $request, Student $student, \App\Models\StudentLeave $leave)
    {
        abort_if($leave->student_id !== $student->id, 404);

        $data = $request->validate([
            'makeup_date'   => ['nullable', 'date'],
            'makeup_status' => ['required', 'in:pending,scheduled,completed,not_required'],
        ]);

        $leave->update($data);

        return back()->with('success', 'อัปเดตสถานะเรียนชดเชยเรียบร้อยแล้ว');
    }
}
