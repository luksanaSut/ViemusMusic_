<?php

namespace Tests\Feature;

use App\Models\ClassSchedule;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\MakeupRequest;
use App\Models\Student;
use App\Models\StudentLeave;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MakeupRequestDualApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_schedule_is_only_created_after_both_admin_and_instructor_approve(): void
    {
        $course = Course::factory()->create(['allow_makeup_class' => true]);
        $student = Student::factory()->create();
        $enrollment = Enrollment::factory()->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);
        $teacher = Teacher::factory()->create(['is_active' => true]);
        $leave = StudentLeave::factory()->create([
            'student_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'leave_type' => 'normal',
            'status' => 'pending',
        ]);
        $makeupRequest = MakeupRequest::factory()->create([
            'student_leave_id' => $leave->id,
            'student_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'teacher_id' => $teacher->id,
            'makeup_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00',
            'end_time' => '11:00',
            'admin_approval_status' => 'pending',
            'instructor_approval_status' => 'pending',
        ]);

        $admin = User::factory()->create(['role' => 'admin']);
        $teacherUser = User::factory()->create(['role' => 'teacher', 'teacher_id' => $teacher->id]);

        // ยังไม่มีตารางเรียนใหม่ตอนเริ่ม
        $this->assertDatabaseCount('class_schedules', 0);

        // Admin อนุมัติฝ่ายเดียว -> ยังไม่ควรสร้างตารางเรียน
        $this->actingAs($admin)->post("/makeup-requests/{$makeupRequest->id}/approve-admin");
        $this->assertDatabaseCount('class_schedules', 0);
        $this->assertEquals('pending', $makeupRequest->fresh()->overall_status);

        // อาจารย์อนุมัติตาม -> ครบ 2 ฝ่าย ควรสร้างตารางเรียนทันที
        $this->actingAs($teacherUser)->post("/makeup-requests/{$makeupRequest->id}/approve-instructor");
        $this->assertDatabaseCount('class_schedules', 1);
        $this->assertEquals('approved', $makeupRequest->fresh()->overall_status);
        $this->assertNotNull($makeupRequest->fresh()->class_schedule_id);
    }

    public function test_rejection_by_either_party_cancels_the_request(): void
    {
        $teacher = Teacher::factory()->create();
        $makeupRequest = MakeupRequest::factory()->create([
            'teacher_id' => $teacher->id,
            'admin_approval_status' => 'pending',
            'instructor_approval_status' => 'pending',
        ]);
        $teacherUser = User::factory()->create(['role' => 'teacher', 'teacher_id' => $teacher->id]);

        $this->actingAs($teacherUser)->post("/makeup-requests/{$makeupRequest->id}/reject", [
            'rejection_reason' => 'ติดภารกิจ',
        ]);

        $this->assertEquals('rejected', $makeupRequest->fresh()->overall_status);
        $this->assertDatabaseCount('class_schedules', 0);
    }
}
