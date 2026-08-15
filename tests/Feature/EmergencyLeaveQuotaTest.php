<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmergencyLeaveQuotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_cannot_exceed_emergency_leave_quota(): void
    {
        $course = Course::factory()->create([
            'emergency_leave_quota' => 1,
            'allow_makeup_class'    => true,
        ]);
        $student = Student::factory()->create();
        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_id'  => $course->id,
            'status'     => 'active',
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        // ครั้งที่ 1: ควรผ่าน
        $response = $this->actingAs($admin)->post("/students/{$student->id}/leaves", [
            'enrollment_id' => $enrollment->id,
            'leave_type'    => 'emergency',
            'leave_date'    => now()->addDay()->toDateString(),
        ]);
        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('student_leaves', [
            'enrollment_id' => $enrollment->id,
            'leave_type'    => 'emergency',
        ]);

        // ครั้งที่ 2: ต้องถูกปฏิเสธเพราะเกินโควตา 1 ครั้ง/คอร์ส
        $response = $this->actingAs($admin)->post("/students/{$student->id}/leaves", [
            'enrollment_id' => $enrollment->id,
            'leave_type'    => 'emergency',
            'leave_date'    => now()->addDays(2)->toDateString(),
        ]);
        $response->assertSessionHas('error');
        $this->assertEquals(1, $student->leaves()->where('leave_type', 'emergency')->count());
    }
}
