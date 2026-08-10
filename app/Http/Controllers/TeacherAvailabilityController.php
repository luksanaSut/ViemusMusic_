<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherAvailabilityController extends Controller
{
    // บันทึกตาราง Availability ทั้งสัปดาห์ในคราวเดียว (ฟอร์มแบบตาราง 7 วัน)
    public function update(Request $request, Teacher $teacher)
    {
        $data = $request->validate([
            'availabilities'                    => ['nullable', 'array'],
            'availabilities.*.day_of_week'      => ['required', 'integer', 'between:0,6'],
            'availabilities.*.start_time'       => ['required'],
            'availabilities.*.end_time'         => ['required'],
            'availabilities.*.is_available'     => ['nullable'],
        ]);

        $teacher->availabilities()->delete();

        foreach ($data['availabilities'] ?? [] as $row) {
            $teacher->availabilities()->create([
                'day_of_week'  => $row['day_of_week'],
                'start_time'   => $row['start_time'],
                'end_time'     => $row['end_time'],
                'is_available' => isset($row['is_available']),
            ]);
        }

        return back()->with('success', 'บันทึกตาราง Availability เรียบร้อยแล้ว');
    }
}
