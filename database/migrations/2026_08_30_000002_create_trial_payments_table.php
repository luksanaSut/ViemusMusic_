<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trial_payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_no')->unique();
            $table->foreignId('trial_lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_payment_id')->nullable()->constrained('trial_payments')->nullOnDelete();
            $table->enum('type', ['payment', 'refund'])->default('payment');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'promptpay', 'credit_card', 'other']);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->dateTime('transaction_at');
            $table->string('reference_no')->nullable();
            $table->string('proof_path')->nullable();
            $table->string('proof_original_name')->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by')->nullable();
            $table->string('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'transaction_at']);
            $table->index(['trial_lead_id', 'type']);
        });

        // รักษายอดที่เคยบันทึกด้วยระบบเดิม ไม่ให้หายจากรายงานหลังอัปเกรด
        DB::table('trial_leads')->where('payment_status', 'paid')->where('trial_fee', '>', 0)
            ->orderBy('id')->each(function ($lead) {
                DB::table('trial_payments')->insert([
                    'transaction_no' => 'TP-LEGACY-' . str_pad($lead->id, 6, '0', STR_PAD_LEFT),
                    'trial_lead_id' => $lead->id, 'type' => 'payment', 'amount' => $lead->trial_fee,
                    'payment_method' => 'other', 'status' => 'confirmed',
                    'transaction_at' => $lead->paid_at ?? $lead->updated_at,
                    'notes' => 'ย้ายจากข้อมูลชำระเงินเดิม', 'created_by' => 'ระบบ', 'confirmed_by' => 'ระบบ',
                    'confirmed_at' => $lead->paid_at ?? $lead->updated_at,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_payments');
    }
};
