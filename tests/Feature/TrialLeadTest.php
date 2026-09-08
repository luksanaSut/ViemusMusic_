<?php

namespace Tests\Feature;

use App\Models\TrialLead;
use App\Models\TrialPayment;
use App\Models\User;
use App\Services\FinanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrialLeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_deletion_preserves_payment_history_and_converted_students(): void
    {
        $admin = User::create([
            'name' => 'Admin', 'email' => 'delete-admin@example.com', 'password' => 'password',
            'role' => 'admin', 'is_active' => true, 'must_change_password' => false,
        ]);
        $this->actingAs($admin);
        $payload = ['student_name' => 'ผู้สนใจทดสอบ', 'phone' => '0800000000', 'delivery_mode' => 'onsite', 'trial_fee' => 500];
        $this->post(route('trial-leads.store'), $payload)->assertSessionHasNoErrors();
        $first = TrialLead::latest('id')->first();
        $this->post(route('trial-leads.store'), $payload)->assertSessionHasNoErrors();
        $second = TrialLead::latest('id')->first();

        $this->get(route('trial-leads.index'))->assertOk()->assertSee(route('trial-leads.destroy', $first));
        $this->get(route('trial-leads.show', $first))->assertOk()->assertSee('DELETE');
        $this->delete(route('trial-leads.destroy', $first))->assertRedirect(route('trial-leads.index'));
        $this->assertDatabaseMissing('trial_leads', ['id' => $first->id]);
        $this->post(route('trial-leads.store'), $payload)->assertSessionHasNoErrors();
        $this->assertDatabaseCount('trial_leads', 2);

        $this->post(route('trial-payments.store', $second), [
            'amount' => 500, 'payment_method' => 'cash', 'transaction_at' => now()->format('Y-m-d H:i:s'),
        ])->assertSessionHasNoErrors();
        $this->from(route('trial-leads.show', $second))->delete(route('trial-leads.destroy', $second))
            ->assertRedirect(route('trial-leads.show', $second))->assertSessionHasErrors('delete');
        $this->assertDatabaseHas('trial_leads', ['id' => $second->id]);
        $this->assertDatabaseHas('trial_payments', ['trial_lead_id' => $second->id]);

        $third = TrialLead::latest('id')->first();
        $this->post(route('trial-leads.convert', $third))->assertRedirect();
        $this->delete(route('trial-leads.destroy', $third))->assertSessionHasErrors('delete');
        $this->assertDatabaseHas('trial_leads', ['id' => $third->id]);
        $this->assertDatabaseHas('students', ['id' => $third->fresh()->converted_student_id]);
    }

    public function test_teacher_cannot_delete_a_trial_lead(): void
    {
        $teacher = User::create([
            'name' => 'Teacher', 'email' => 'delete-teacher@example.com', 'password' => 'password',
            'role' => 'teacher', 'is_active' => true, 'must_change_password' => false,
        ]);
        $lead = TrialLead::create([
            'lead_no' => 'TL-DELETE-TEST', 'student_name' => 'ผู้สนใจ', 'phone' => '0800000000',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'status' => 'new',
        ]);
        $this->actingAs($teacher)->delete(route('trial-leads.destroy', $lead))->assertForbidden();
        $this->assertDatabaseHas('trial_leads', ['id' => $lead->id]);
    }

    public function test_unpaid_lead_can_pay_later_from_details_page(): void
    {
        $admin = User::create([
            'name' => 'Admin', 'email' => 'pay-later@example.com', 'password' => 'password',
            'role' => 'admin', 'is_active' => true, 'must_change_password' => false,
        ]);
        $this->actingAs($admin)->post(route('trial-leads.store'), [
            'student_name' => 'ชำระภายหลัง', 'phone' => '0800000000',
            'delivery_mode' => 'onsite', 'trial_fee' => 500,
        ])->assertSessionHasNoErrors();
        $lead = TrialLead::latest('id')->first();
        $this->assertSame('unpaid', $lead->payment_status);
        $this->assertDatabaseCount('trial_payments', 0);
        $this->get(route('trial-leads.show', $lead))->assertOk()
            ->assertSee(route('trial-payments.store', $lead))
            ->assertSee('บันทึกรับชำระค่าทดลอง');
        $this->from(route('trial-leads.show', $lead))->post(route('trial-payments.store', $lead), [
            'amount' => 500, 'payment_method' => 'cash', 'transaction_at' => now()->format('Y-m-d H:i:s'),
        ])->assertRedirect(route('trial-leads.show', $lead))->assertSessionHasNoErrors();
        $this->assertSame('paid', $lead->fresh()->payment_status);
        $this->assertSame(500.0, $lead->confirmedPaidAmount());
        $this->get(route('trial-leads.show', $lead))->assertOk()->assertDontSee('บันทึกรับชำระค่าทดลอง');
    }

    public function test_admin_can_create_a_trial_lead(): void
    {
        $admin = User::create([
            'name' => 'Admin', 'email' => 'trial-admin@example.com', 'password' => 'password',
            'role' => 'admin', 'is_active' => true, 'must_change_password' => false,
        ]);

        $response = $this->actingAs($admin)->post(route('trial-leads.store'), [
            'student_name' => 'เด็กทดลอง', 'guardian_name' => 'ผู้ปกครองทดลอง', 'phone' => '0812345678',
            'delivery_mode' => 'onsite', 'trial_fee' => 500, 'payment_status' => 'unpaid',
        ]);

        $lead = TrialLead::first();
        $response->assertRedirect(route('trial-leads.show', $lead));
        $this->assertSame('new', $lead->status);
        $this->assertStringStartsWith('TL-', $lead->lead_no);
    }

    public function test_admin_can_convert_a_trial_lead_to_student(): void
    {
        $admin = User::create([
            'name' => 'Admin', 'email' => 'convert-admin@example.com', 'password' => 'password',
            'role' => 'admin', 'is_active' => true, 'must_change_password' => false,
        ]);
        $lead = TrialLead::create([
            'lead_no' => 'TL-TEST-0001', 'student_name' => 'เด็กพร้อมสมัคร', 'phone' => '0899999999',
            'delivery_mode' => 'onsite', 'trial_fee' => 0, 'payment_status' => 'waived',
            'status' => 'completed', 'trial_result' => 'interested',
        ]);

        $response = $this->actingAs($admin)->post(route('trial-leads.convert', $lead));

        $lead->refresh();
        $response->assertRedirect(route('students.show', $lead->converted_student_id));
        $this->assertSame('converted', $lead->status);
        $this->assertDatabaseHas('students', ['id' => $lead->converted_student_id, 'full_name' => 'เด็กพร้อมสมัคร']);
    }

    public function test_cash_payment_is_confirmed_and_included_in_finance_then_refund_is_deducted(): void
    {
        $admin = User::create([
            'name' => 'Admin', 'email' => 'payment-admin@example.com', 'password' => 'password',
            'role' => 'admin', 'is_active' => true, 'must_change_password' => false,
        ]);
        $lead = TrialLead::create([
            'lead_no' => 'TL-PAY-0001', 'student_name' => 'เด็กชำระเงิน', 'phone' => '0877777777',
            'delivery_mode' => 'onsite', 'trial_fee' => 500, 'payment_status' => 'unpaid', 'status' => 'scheduled',
        ]);

        $this->actingAs($admin)->post(route('trial-payments.store', $lead), [
            'amount' => 500, 'payment_method' => 'cash', 'transaction_at' => now()->format('Y-m-d H:i:s'),
        ])->assertSessionHasNoErrors();

        $payment = TrialPayment::first();
        $this->assertSame('confirmed', $payment->status);
        $this->assertSame(500.0, app(FinanceService::class)->trialIncome(now()->startOfDay(), now()->endOfDay()));

        $this->actingAs($admin)->post(route('trial-payments.refund', $payment), [
            'amount' => 200, 'notes' => 'ผู้ปกครองยกเลิกนัด',
        ])->assertSessionHasNoErrors();

        $this->assertSame(300.0, app(FinanceService::class)->trialIncome(now()->startOfDay(), now()->endOfDay()));
        $this->assertSame(300.0, $lead->fresh()->confirmedPaidAmount());
    }
}
