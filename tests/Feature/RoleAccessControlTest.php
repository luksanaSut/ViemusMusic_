<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/teachers');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_access_teacher_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/teachers');
        $response->assertOk();
    }

    public function test_teacher_role_cannot_access_teacher_management(): void
    {
        $teacherRecord = Teacher::factory()->create();
        $teacherUser = User::factory()->create(['role' => 'teacher', 'teacher_id' => $teacherRecord->id]);

        $response = $this->actingAs($teacherUser)->get('/teachers');
        $response->assertForbidden();
    }

    public function test_teacher_cannot_approve_makeup_request_of_another_teacher(): void
    {
        $teacherA = Teacher::factory()->create();
        $teacherB = Teacher::factory()->create();
        $userA = User::factory()->create(['role' => 'teacher', 'teacher_id' => $teacherA->id]);

        $makeupRequest = \App\Models\MakeupRequest::factory()->create(['teacher_id' => $teacherB->id]);

        $response = $this->actingAs($userA)->post("/makeup-requests/{$makeupRequest->id}/approve-instructor");
        $response->assertForbidden();
    }
}
