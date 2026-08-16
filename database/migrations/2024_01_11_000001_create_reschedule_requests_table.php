<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reschedule_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['change', 'swap']);

            $table->foreignId('class_schedule_id')->constrained()->cascadeOnDelete(); // คาบที่ขอเปลี่ยน
            $table->foreignId('swap_with_class_schedule_id')->nullable()->constrained('class_schedules')->nullOnDelete(); // คาบที่จะแลกด้วย (เฉพาะ type=swap)

            // ค่าที่ขอเปลี่ยน (เฉพาะ type=change)
            $table->foreignId('new_teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->foreignId('new_room_id')->nullable()->constrained('rooms')->nullOnDelete();
            $table->date('new_date')->nullable();
            $table->time('new_start_time')->nullable();
            $table->time('new_end_time')->nullable();

            // เก็บค่าดั้งเดิมไว้ ใช้แสดงประวัติเปรียบเทียบก่อน-หลัง
            $table->json('snapshot_before')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('requested_by')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reschedule_requests');
    }
};
