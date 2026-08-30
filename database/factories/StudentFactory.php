<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'student_code' => fake()->unique()->bothify('ST-#####'),
            'full_name' => fake()->name(),
            'nickname' => fake()->firstName(),
            'date_of_birth' => fake()->dateTimeBetween('-18 years', '-5 years')->format('Y-m-d'),
            'phone' => '08' . fake()->numerify('########'),
            'status' => 'active',
        ];
    }
}
