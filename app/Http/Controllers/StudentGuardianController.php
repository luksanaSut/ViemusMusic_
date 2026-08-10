<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentGuardianController extends Controller
{
    // POST /students/{student}/guardians — ผูกผู้ปกครอง (ที่มีอยู่แล้ว หรือสร้างใหม่) เข้ากับนักเรียน
    public function store(Request $request, Student $student)
    {
        $data = $request->validate([
            'guardian_id'   => ['nullable', 'exists:guardians,id'],
            'full_name'     => ['required_without:guardian_id', 'nullable', 'string', 'max:150'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'relation'      => ['nullable', 'string', 'max:50'],
            'is_primary'    => ['nullable', 'boolean'],
        ]);

        // ถ้าไม่ได้เลือกผู้ปกครองที่มีอยู่ ให้สร้างใหม่ (แล้วจะไปโผล่ในเมนู "จัดการผู้ปกครอง" ทันที เพราะเป็นตารางเดียวกัน)
        if (empty($data['guardian_id'])) {
            $guardian = Guardian::create([
                'full_name' => trim(strip_tags($data['full_name'])),
                'phone'     => $data['phone'] ? preg_replace('/\D/', '', $data['phone']) : null,
            ]);
        } else {
            $guardian = Guardian::findOrFail($data['guardian_id']);
        }

        if ($request->boolean('is_primary')) {
            // มีผู้ปกครองหลักได้แค่คนเดียวต่อนักเรียน
            $student->guardians()->updateExistingPivot($student->guardians->pluck('id'), ['is_primary' => false]);
        }

        $student->guardians()->syncWithoutDetaching([
            $guardian->id => [
                'relation'   => $data['relation'] ?? null,
                'is_primary' => $request->boolean('is_primary'),
            ],
        ]);

        return back()->with('success', 'เพิ่มผู้ปกครองให้นักเรียนเรียบร้อยแล้ว');
    }

    // DELETE /students/{student}/guardians/{guardian} — เอาผู้ปกครองออกจากนักเรียนคนนี้ (ไม่ได้ลบผู้ปกครองทิ้ง)
    public function destroy(Student $student, Guardian $guardian)
    {
        $student->guardians()->detach($guardian->id);

        return back()->with('success', 'นำผู้ปกครองออกจากนักเรียนคนนี้แล้ว');
    }
}
