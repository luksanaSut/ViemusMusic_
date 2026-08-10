<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_no')->unique();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('old_enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('old_course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('new_course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('new_teacher_id')->nullable()->constrained('teachers')->nullOnDelete();

            // คำนวณส่วนต่างราคาอัตโนมัติ
            $table->decimal('old_course_remaining_value', 10, 2); // มูลค่าคงเหลือของคอร์สเดิม
            $table->decimal('new_course_price', 10, 2);
            $table->decimal('teacher_change_fee', 10, 2)->default(0); // ค่าธรรมเนียมเปลี่ยนอาจารย์ (ถ้ามี, กรอกเอง)
            $table->decimal('price_difference', 10, 2); // บวก = ต้องจ่ายเพิ่ม, ลบ = ได้เครดิตคืน

            // Business rule: ราคาสูงกว่า -> ต้องจ่ายก่อนยืนยัน / ราคาต่ำกว่า -> เก็บเครดิต
            $table->enum('payment_status', ['not_required', 'pending_payment', 'paid'])->default('not_required');
            $table->decimal('credit_issued', 10, 2)->default(0);

            $table->enum('status', ['pending_payment', 'completed', 'cancelled'])->default('pending_payment');

            $table->foreignId('new_enrollment_id')->nullable()->constrained('enrollments')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();

            $table->string('payment_method')->nullable();
            $table->string('payment_reference')->nullable();
            $table->string('payment_proof_path')->nullable();

            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('transferred_by')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_transfers');
    }
};
