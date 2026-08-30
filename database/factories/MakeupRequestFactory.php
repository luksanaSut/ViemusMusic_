<?php

namespace Database\Factories;

use App\Models\MakeupRequest;
use App\Models\StudentLeave;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class MakeupRequestFactory extends Factory
{
    protected $model = MakeupRequest::class;

    public function definition(): array
    {
        return [
            'student_leave_id' => StudentLeave::factory(),
            'student_id' => fn (array $attributes) => StudentLeave::find($attributes['student_leave_id'])->student_id,
            'enrollment_id' => fn (array $attributes) => StudentLeave::find($attributes['student_leave_id'])->enrollment_id,
            'teacher_id' => Teacher::factory(),
            'makeup_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'delivery_mode' => 'onsite',
            'admin_approval_status' => 'pending',
            'instructor_approval_status' => 'pending',
            'overall_status' => 'pending',
            'is_overdue' => false,
        ];
    }
}
