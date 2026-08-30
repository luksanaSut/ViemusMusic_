<?php

namespace Tests\Feature;

use App\Models\Teacher;
use App\Models\TrialLead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrialLeadTeacherWorkflowTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'name' => 'Admin', 'email' => 'admin-'.uniqid().'@example.com', 'password' => 'password',
            'role' => 'admin', 'is_active' => true, 'must_change_password' => false,
        ]);
    }

    private function teacherUser(Teacher $teacher): User
    {
        return User::create([
            'name' => $teacher->full_name, 'email' => 'teacher-'.uniqid().'@example.com', 'password' => 'password',
            'role' => 'teacher', 'teacher_id' => $teacher->id, 'is_active' => true, 'must_change_password' => false,
        ]);
    }

    public function test_teacher_is_notified_and_can_view_only_their_own_trial_lead(): void
    {
        $admin = $this->admin();
        $teacherA = Teacher::create(['teacher_code' => 'T-TEST-A', 'full_name' => 'ครูเอ', 'is_active' => true]);
        $teacherB = Teacher::create(['teacher_code' => 'T-TEST-B', 'full_name' => 'ครูบี', 'is_active' => true]);
        $userA = $this->teacherUser($teacherA);
        $userB = $this->teacherUser($teacherB);

        $response = $this->actingAs($admin)->post(route('trial-leads.store'), [
            'student_name' => 'เด็กทดลอง', 'phone' => '0812345678',
            'delivery_mode' => 'onsite', 'trial_fee' => 500,
            'teacher_id' => $teacherA->id,
            'trial_date' => now()->addDay()->format('Y-m-d'),
            'trial_start_time' => '10:00', 'trial_end_time' => '10:30',
        ]);
        $response->assertSessionHasNoErrors();
        $lead = TrialLead::where('student_name', 'เด็กทดลอง')->firstOrFail();

        $this->assertDatabaseHas('app_notifications', [
            'recipient_role' => 'teacher', 'recipient_id' => $teacherA->id,
        ]);

        $this->actingAs($userA)->get(route('trial-leads.my-index'))->assertOk()->assertSee('เด็กทดลอง');
        $this->actingAs($userA)->get(route('trial-leads.my-show', $lead))->assertOk();
        $this->actingAs($userB)->get(route('trial-leads.my-show', $lead))->assertForbidden();
    }

    public function test_teacher_confirm_check_in_and_submit_result_flow(): void
    {
        $teacher = Teacher::create(['teacher_code' => 'T-TEST-C', 'full_name' => 'ครูซี', 'is_active' => true]);
        $user = $this->teacherUser($teacher);
        $lead = TrialLead::create([
            'lead_no' => 'TL-FLOW-0001', 'student_name' => 'เด็กโฟลว์', 'phone' => '0899999999',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'payment_status' => 'waived',
            'status' => 'scheduled', 'teacher_id' => $teacher->id,
            'trial_date' => now()->format('Y-m-d'), 'trial_start_time' => '09:00', 'trial_end_time' => '09:30',
        ]);

        $this->actingAs($user)->post(route('trial-leads.teacher-confirm', $lead))->assertRedirect();
        $lead->refresh();
        $this->assertNotNull($lead->teacher_confirmed_at);
        $this->assertSame('teacher_confirmed', $lead->confirmation_status);

        $this->actingAs($user)->post(route('trial-leads.check-in', $lead))->assertRedirect();
        $lead->refresh();
        $this->assertNotNull($lead->checked_in_at);

        $this->actingAs($user)->post(route('trial-leads.submit-result', $lead), [
            'trial_result' => 'interested', 'teacher_feedback' => 'พื้นฐานดี แนะนำคอร์สเปียโนเบื้องต้น',
        ])->assertRedirect(route('trial-leads.my-show', $lead));
        $lead->refresh();
        $this->assertSame('interested', $lead->trial_result);
        $this->assertSame('completed', $lead->status);
        $this->assertNotNull($lead->result_recorded_at);
        $this->assertSame($user->displayName(), $lead->result_recorded_by);
    }

    public function test_teacher_cannot_check_in_or_record_result_before_trial_start(): void
    {
        $teacher = Teacher::create(['teacher_code' => 'T-TEST-F', 'full_name' => 'ครูเอฟ', 'is_active' => true]);
        $user = $this->teacherUser($teacher);
        $lead = TrialLead::create([
            'lead_no' => 'TL-EARLY-0001', 'student_name' => 'เด็กก่อนเวลา', 'phone' => '0844444444',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'payment_status' => 'waived',
            'status' => 'scheduled', 'teacher_id' => $teacher->id,
            'trial_date' => now()->addDay()->toDateString(), 'trial_start_time' => '09:00', 'trial_end_time' => '09:30',
        ]);

        $this->actingAs($user)->post(route('trial-leads.check-in', $lead))
            ->assertSessionHasErrors('trial');
        $this->actingAs($user)->post(route('trial-leads.submit-result', $lead), [
            'trial_result' => 'no_show',
        ])->assertSessionHasErrors('trial_result');

        $lead->refresh();
        $this->assertNull($lead->checked_in_at);
        $this->assertNull($lead->trial_result);
    }

    public function test_normal_result_requires_check_in(): void
    {
        $teacher = Teacher::create(['teacher_code' => 'T-TEST-G', 'full_name' => 'ครูจี', 'is_active' => true]);
        $user = $this->teacherUser($teacher);
        $lead = TrialLead::create([
            'lead_no' => 'TL-NOCHECK-0001', 'student_name' => 'เด็กไม่เช็กอิน', 'phone' => '0833333333',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'payment_status' => 'waived',
            'status' => 'scheduled', 'teacher_id' => $teacher->id,
            'trial_date' => now()->subDay()->toDateString(), 'trial_start_time' => '09:00', 'trial_end_time' => '09:30',
        ]);

        $this->actingAs($user)->post(route('trial-leads.submit-result', $lead), [
            'trial_result' => 'interested',
        ])->assertSessionHasErrors('trial_result');

        $this->assertNull($lead->fresh()->trial_result);
    }

    public function test_teacher_can_record_no_show_after_start_without_check_in(): void
    {
        $teacher = Teacher::create(['teacher_code' => 'T-TEST-H', 'full_name' => 'ครูเอช', 'is_active' => true]);
        $user = $this->teacherUser($teacher);
        $lead = TrialLead::create([
            'lead_no' => 'TL-NOSHOW-0001', 'student_name' => 'เด็กไม่มา', 'phone' => '0822222222',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'payment_status' => 'waived',
            'status' => 'scheduled', 'teacher_id' => $teacher->id,
            'trial_date' => now()->subDay()->toDateString(), 'trial_start_time' => '09:00', 'trial_end_time' => '09:30',
        ]);

        $this->actingAs($user)->post(route('trial-leads.submit-result', $lead), [
            'trial_result' => 'no_show',
        ])->assertRedirect(route('trial-leads.my-show', $lead));

        $lead->refresh();
        $this->assertSame('no_show', $lead->trial_result);
        $this->assertSame('no_show', $lead->confirmation_status);
        $this->assertSame('completed', $lead->status);
        $this->assertNotNull($lead->result_recorded_at);
        $this->assertSame($user->displayName(), $lead->result_recorded_by);
    }

    public function test_teacher_cannot_mark_checked_in_student_as_no_show(): void
    {
        $teacher = Teacher::create(['teacher_code' => 'T-TEST-I', 'full_name' => 'ครูไอ', 'is_active' => true]);
        $user = $this->teacherUser($teacher);
        $lead = TrialLead::create([
            'lead_no' => 'TL-CHECKED-0001', 'student_name' => 'เด็กมาแล้ว', 'phone' => '0811111111',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'payment_status' => 'waived',
            'status' => 'scheduled', 'teacher_id' => $teacher->id,
            'trial_date' => now()->subDay()->toDateString(), 'trial_start_time' => '09:00', 'trial_end_time' => '09:30',
            'checked_in_at' => now(), 'checked_in_by' => $user->displayName(),
        ]);

        $this->actingAs($user)->post(route('trial-leads.submit-result', $lead), [
            'trial_result' => 'no_show',
        ])->assertSessionHasErrors('trial_result');

        $this->assertNull($lead->fresh()->trial_result);
    }

    public function test_guardian_and_teacher_confirmation_derives_fully_confirmed(): void
    {
        $admin = $this->admin();
        $teacher = Teacher::create(['teacher_code' => 'T-TEST-D', 'full_name' => 'ครูดี', 'is_active' => true]);
        $user = $this->teacherUser($teacher);
        $lead = TrialLead::create([
            'lead_no' => 'TL-CONF-0001', 'student_name' => 'เด็กคอนเฟิร์ม', 'phone' => '0877777777',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'payment_status' => 'waived',
            'status' => 'scheduled', 'teacher_id' => $teacher->id,
        ]);

        $this->actingAs($admin)->post(route('trial-leads.confirmation-status', $lead), [
            'confirmation_status' => 'guardian_confirmed',
        ])->assertSessionHasNoErrors();
        $lead->refresh();
        $this->assertSame('guardian_confirmed', $lead->confirmation_status);

        $this->actingAs($user)->post(route('trial-leads.teacher-confirm', $lead));
        $lead->refresh();
        $this->assertSame('fully_confirmed', $lead->confirmation_status);
    }

    public function test_staff_can_set_terminal_confirmation_statuses(): void
    {
        $admin = $this->admin();
        $lead = TrialLead::create([
            'lead_no' => 'TL-TERM-0001', 'student_name' => 'เด็กยกเลิก', 'phone' => '0866666666',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'payment_status' => 'waived', 'status' => 'new',
        ]);

        foreach (['unreachable', 'reschedule_requested', 'cancelled', 'no_show', 'pending'] as $status) {
            $this->actingAs($admin)->post(route('trial-leads.confirmation-status', $lead), [
                'confirmation_status' => $status, 'confirmation_notes' => 'ทดสอบ',
            ])->assertSessionHasNoErrors();
            $this->assertSame($status, $lead->fresh()->confirmation_status);
        }
    }

    public function test_calendar_and_dashboard_pages_surface_trial_leads(): void
    {
        $admin = $this->admin();
        $teacher = Teacher::create(['teacher_code' => 'T-TEST-E', 'full_name' => 'ครูอี', 'is_active' => true]);
        $user = $this->teacherUser($teacher);
        $room = \App\Models\Room::create(['room_code' => 'R-TEST-A', 'name' => 'ห้อง A', 'capacity' => 5, 'is_active' => true]);

        TrialLead::create([
            'lead_no' => 'TL-CAL-0001', 'student_name' => 'เด็กปฏิทิน', 'phone' => '0855555555',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'payment_status' => 'waived', 'status' => 'scheduled',
            'teacher_id' => $teacher->id, 'room_id' => $room->id,
            'trial_date' => now()->toDateString(), 'trial_start_time' => '14:00', 'trial_end_time' => '14:30',
        ]);

        $this->actingAs($admin)->get(route('schedules.index'))->assertOk()->assertSee('เด็กปฏิทิน');
        $this->actingAs($admin)->get(route('rooms.schedule'))->assertOk()->assertSee('เด็กปฏิทิน');
        $this->actingAs($admin)->get(route('dashboard'))->assertOk()->assertSee('เด็กปฏิทิน');
        $this->actingAs($user)->get(route('teacher.schedule'))->assertOk()->assertSee('เด็กปฏิทิน');
        $this->actingAs($user)->get(route('teacher.tasks'))->assertOk()->assertSee('เด็กปฏิทิน');
        $this->actingAs($user)->get(route('dashboard'))->assertOk()->assertSee('เด็กปฏิทิน');
    }
}
