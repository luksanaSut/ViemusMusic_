<?php

namespace Tests\Feature;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoomCapacityTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_cannot_exceed_room_capacity(): void
    {
        $room = Room::factory()->create(['capacity' => 5, 'is_active' => true, 'is_under_maintenance' => false]);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post("/rooms/{$room->id}/bookings", [
            'booking_date'    => now()->addDay()->toDateString(),
            'start_time'      => '10:00',
            'end_time'        => '11:00',
            'attendees_count' => 10, // เกินความจุ 5 คน
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('room_bookings', 0);
    }

    public function test_booking_within_capacity_succeeds(): void
    {
        $room = Room::factory()->create(['capacity' => 5, 'is_active' => true, 'is_under_maintenance' => false]);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post("/rooms/{$room->id}/bookings", [
            'booking_date'    => now()->addDay()->toDateString(),
            'start_time'      => '10:00',
            'end_time'        => '11:00',
            'attendees_count' => 3,
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseCount('room_bookings', 1);
    }

    public function test_overlapping_booking_is_rejected(): void
    {
        $room = Room::factory()->create(['capacity' => 20, 'is_active' => true, 'is_under_maintenance' => false]);
        $admin = User::factory()->create(['role' => 'admin']);
        $date = now()->addDay()->toDateString();

        $this->actingAs($admin)->post("/rooms/{$room->id}/bookings", [
            'booking_date' => $date,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'attendees_count' => 2,
        ]);

        $response = $this->actingAs($admin)->post("/rooms/{$room->id}/bookings", [
            'booking_date' => $date,
            'start_time' => '10:30',
            'end_time' => '11:30',
            'attendees_count' => 2,
        ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseCount('room_bookings', 1);
    }
}
