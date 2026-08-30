<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'room_code' => fake()->unique()->bothify('R-###'),
            'name' => 'ห้อง ' . fake()->unique()->word(),
            'capacity' => 5,
            'is_active' => true,
            'is_under_maintenance' => false,
        ];
    }
}
