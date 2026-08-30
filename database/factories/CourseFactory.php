<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'course_code' => fake()->unique()->bothify('C-####'),
            'name' => 'คอร์ส ' . fake()->unique()->word(),
            'description' => fake()->optional()->sentence(),
            'structure_type' => 'regular',
            'class_type' => 'private',
            'delivery_mode' => 'onsite',
            'total_sessions' => 12,
            'duration_months' => 3,
            'price' => 6000,
            'max_students' => null,
            'allow_makeup_class' => true,
            'emergency_leave_quota' => 1,
            'is_adult_flexi' => false,
            'is_active' => true,
        ];
    }
}
