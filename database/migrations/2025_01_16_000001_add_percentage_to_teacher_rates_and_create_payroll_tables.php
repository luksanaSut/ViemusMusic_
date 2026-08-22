<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // เพิ่มประเภทเรท "รายเปอร์เซ็นต์" เข้าไปในระบบเรทค่าจ้างเดิม
        DB::statement("ALTER TABLE teacher_rates MODIFY rate_type ENUM('per_hour','per_session','monthly_fixed','percentage') NOT NULL");

        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');

            $table->decimal('teaching_income_total', 10, 2)->default(0); // รวมจากคลาสที่สอนจริงเท่านั้น
            $table->decimal('transport_fee_total', 10, 2)->default(0);
            $table->decimal('adjustment_amount', 10, 2)->default(0); // บวก = โบนัส, ลบ = หัก
            $table->text('adjustment_reason')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);

            $table->enum('status', ['draft', 'confirmed', 'paid'])->default('draft');
            $table->date('paid_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('generated_by')->nullable();
            $table->string('adjusted_by')->nullable();
            $table->string('paid_by')->nullable();
            $table->timestamps();

            $table->unique(['teacher_id', 'period_start', 'period_end']);
        });

        Schema::create('payroll_run_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teaching_session_id')->constrained()->cascadeOnDelete();
            $table->decimal('income_amount', 10, 2);
            $table->timestamps();

            $table->unique(['payroll_run_id', 'teaching_session_id'], 'payroll_run_items_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_run_items');
        Schema::dropIfExists('payroll_runs');
        DB::statement("ALTER TABLE teacher_rates MODIFY rate_type ENUM('per_hour','per_session','monthly_fixed') NOT NULL");
    }
};