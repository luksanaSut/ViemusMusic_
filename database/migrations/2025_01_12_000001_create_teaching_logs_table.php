<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();

            // เช็คชื่อ
            $table->enum('attendance_status', ['present', 'late', 'absent', 'excused_leave'])->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->string('checked_in_by')->nullable();

            // ยืนยันเวลาสอนจริง (30/45/60 หรือกำหนดเอง = สอนเพิ่ม)
            $table->unsignedSmallInteger('confirmed_duration_minutes')->nullable();
            $table->boolean('is_extra_time')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->string('confirmed_by')->nullable();

            // เชื่อมโยงระบบอื่น
            $table->foreignId('student_leave_id')->nullable()->constrained('student_leaves')->nullOnDelete(); // ผูกกับคำขอลาถ้ามี
            $table->foreignId('teaching_session_id')->nullable()->constrained('teaching_sessions')->nullOnDelete(); // ผูกกับรายการเงินเดือน
            $table->boolean('session_deducted')->default(false); // ตัดจำนวนครั้งเรียนของคอร์สไปแล้วหรือยัง

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['teacher_id', 'attendance_status']);
            $table->index(['student_id', 'attendance_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_logs');
    }
};