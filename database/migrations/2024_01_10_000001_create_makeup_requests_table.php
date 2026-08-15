<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('makeup_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_leave_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('original_class_schedule_id')->nullable()->constrained('class_schedules')->nullOnDelete();

            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete(); // อาจารย์ที่สอนชดเชย (เลือกคนอื่นได้)
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();

            $table->date('makeup_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('delivery_mode', ['onsite', 'online', 'hybrid'])->default('onsite');

            $table->enum('admin_approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('admin_reviewed_by')->nullable();
            $table->timestamp('admin_reviewed_at')->nullable();

            $table->enum('instructor_approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('instructor_reviewed_at')->nullable();

            $table->enum('overall_status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])->default('pending');
            $table->foreignId('class_schedule_id')->nullable()->constrained('class_schedules')->nullOnDelete();

            $table->boolean('is_overdue')->default(false); // ลาเกินกำหนดตามนโยบาย ต้องแจ้งแอดมินทราบ
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->index(['overall_status', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('makeup_requests');
    }
};
