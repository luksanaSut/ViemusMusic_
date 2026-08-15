<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete(); // เชื่อมกับคอร์สที่สมัครเรียนจริง
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete(); // null ได้ถ้าเรียนออนไลน์

            $table->date('schedule_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->enum('delivery_mode', ['onsite', 'online', 'hybrid'])->default('onsite');
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'no_show'])->default('scheduled');

            $table->text('notes')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->index(['schedule_date', 'status']);
            $table->index('teacher_id');
            $table->index('room_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
