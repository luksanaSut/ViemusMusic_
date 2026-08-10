<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();

            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('purpose')->nullable(); // เช่น สอนเปียโน, ประชุม, ซ้อมดนตรี
            $table->unsignedInteger('attendees_count')->default(1); // Business rule: ต้องไม่เกิน room.capacity

            $table->enum('status', ['confirmed', 'cancelled'])->default('confirmed');
            $table->string('booked_by')->nullable();
            $table->timestamps();

            $table->index(['room_id', 'booking_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};
