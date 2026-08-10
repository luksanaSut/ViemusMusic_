<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentEnrollmentController extends Controller
{
    // POST /students/{student}/enrollments — ลงทะเบียนเรียนคอร์สใหม่
    public function store(Request $request, Student $student)
    {
        $data = $request->validate([
            'course_id'          => ['required', 'exists:courses,id'],
            'enrolled_date'      => ['required', 'date'],
            'expected_end_date'  => ['nullable', 'date', 'after_or_equal:enrolled_date'],
        ]);

        $data['student_id'] = $student->id;
        $data['status'] = 'active';

        Enrollment::create($data);

        return back()->with('success', 'ลงทะเบียนคอร์สเรียนเรียบร้อยแล้ว');
    }

    // PATCH /students/{student}/enrollments/{enrollment}/status
    public function updateStatus(Request $request, Student $student, Enrollment $enrollment)
    {
        abort_if($enrollment->student_id !== $student->id, 404);

        $data = $request->validate(['status' => ['required', 'in:active,completed,cancelled,paused']]);
        $enrollment->update($data);

        if ($data['status'] === 'completed' && !$enrollment->actual_end_date) {
            $enrollment->update(['actual_end_date' => now()->toDateString()]);
        }

        return back()->with('success', 'อัปเดตสถานะคอร์สเรียนเรียบร้อยแล้ว');
    }

    // POST /students/{student}/enrollments/{enrollment}/extend
    // Business rule: ขยายเวลาได้ตามสิทธิ์ของคอร์ส (3 เดือน→1, 6 เดือน→2, 12 เดือน→ขยายไม่ได้)
    public function extend(Request $request, Student $student, Enrollment $enrollment)
    {
        abort_if($enrollment->student_id !== $student->id, 404);

        $data = $request->validate([
            'extend_months' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        if (!$enrollment->canExtend()) {
            return back()->with('error', 'คอร์สนี้ไม่มีสิทธิ์ขยายเวลาเพิ่มแล้ว (ใช้สิทธิ์ครบตามนโยบายของคอร์ส หรือคอร์สนี้ไม่อนุญาตให้ขยายเวลา)');
        }

        if ($data['extend_months'] > $enrollment->remainingExtensionMonths()) {
            return back()->with('error', "ขยายได้สูงสุด {$enrollment->remainingExtensionMonths()} เดือนเท่านั้นตามสิทธิ์ที่เหลือของคอร์สนี้");
        }

        $enrollment->update([
            'extension_months_used' => $enrollment->extension_months_used + $data['extend_months'],
            'expected_end_date'     => ($enrollment->expected_end_date ?? now())->copy()->addMonths($data['extend_months']),
        ]);

        return back()->with('success', "ขยายเวลาเรียนเพิ่ม {$data['extend_months']} เดือนเรียบร้อยแล้ว");
    }
}
