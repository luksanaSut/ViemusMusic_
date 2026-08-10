<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ประวัติ/ตารางการสอนจริง ใช้คำนวณ ชม.สอน และรายได้ย้อนหลัง
        Schema::create('teaching_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teaching_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained()->nullOnDelete();

            $table->string('student_name')->nullable();
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('hours', 5, 2); // จำนวนชั่วโมงสอน คำนวณจาก start/end

            $table->decimal('rate_applied', 10, 2)->nullable();   // เรทที่ใช้ ณ ตอนสอน
            $table->decimal('transport_fee_applied', 10, 2)->default(0);
            $table->decimal('income_amount', 10, 2)->default(0);  // hours*rate (หรือ fixed) + ค่ารถ

            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['teacher_id', 'session_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_sessions');
    }
};
