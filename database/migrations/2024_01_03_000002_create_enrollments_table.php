<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();

            $table->date('enrolled_date');
            $table->date('expected_end_date')->nullable();
            $table->date('actual_end_date')->nullable();

            $table->unsignedInteger('sessions_used')->default(0);

            // Business rule: ติดตามจำนวนเดือนที่ขยายไปแล้ว เทียบกับสิทธิ์สูงสุดของคอร์ส (Course::maxExtensionMonths())
            $table->unsignedTinyInteger('extension_months_used')->default(0);

            $table->enum('status', ['active', 'completed', 'cancelled', 'paused'])->default('active');
            $table->timestamps();

            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
