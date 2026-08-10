<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentAcademicController extends Controller
{
    // POST /students/{student}/skill-levels
    public function storeSkillLevel(Request $request, Student $student)
    {
        $data = $request->validate([
            'instrument_id' => ['required', 'exists:instruments,id'],
            'level_id'      => ['required', 'exists:levels,id'],
            'assessed_date' => ['nullable', 'date'],
            'note'          => ['nullable', 'string', 'max:500'],
        ]);

        // อัปเดตถ้ามีอยู่แล้ว (unique ต่อ instrument), เพิ่มใหม่ถ้ายังไม่มี
        $student->skillLevels()->updateOrCreate(
            ['instrument_id' => $data['instrument_id']],
            [
                'level_id'      => $data['level_id'],
                'assessed_date' => $data['assessed_date'] ?? now()->toDateString(),
                'note'          => $data['note'] ?? null,
            ]
        );

        return back()->with('success', 'บันทึก Skill Level เรียบร้อยแล้ว');
    }

    // POST /students/{student}/exam-results
    public function storeExamResult(Request $request, Student $student)
    {
        $data = $request->validate([
            'instrument_id'  => ['nullable', 'exists:instruments,id'],
            'exam_board'     => ['required', 'in:abrsm,trinity'],
            'grade'          => ['required', 'string', 'max:50'],
            'exam_date'      => ['required', 'date'],
            'result'         => ['nullable', 'in:distinction,merit,pass,fail'],
            'score'          => ['nullable', 'string', 'max:20'],
            'certificate_no' => ['nullable', 'string', 'max:50'],
            'note'           => ['nullable', 'string', 'max:500'],
        ]);

        $student->examResults()->create($data);

        return back()->with('success', 'บันทึกผลสอบเรียบร้อยแล้ว');
    }
}
