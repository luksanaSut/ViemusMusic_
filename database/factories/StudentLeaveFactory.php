<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\StudentLeave;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentLeaveFactory extends Factory
{
    protected $model = StudentLeave::class;

    public function definition(): array
    {
        return [
            'enrollment_id' => Enrollment::factory(),
            'student_id' => fn (array $attributes) => Enrollment::find($attributes['enrollment_id'])->student_id,
            'leave_type' => 'normal',
            'leave_date' => now()->addDay()->toDateString(),
            'status' => 'pending',
            'is_makeup_required' => true,
            'makeup_status' => 'pending',
        ];
    }
}
