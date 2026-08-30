<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'teacher_code' => fake()->unique()->bothify('T-#####'),
            'full_name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'employment_type' => 'freelance',
            'is_active' => true,
        ];
    }
}
