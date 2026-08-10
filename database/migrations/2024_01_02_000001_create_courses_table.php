<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('course_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();

            $table->foreignId('instrument_id')->nullable()->constrained()->nullOnDelete(); // ประเภทเครื่องดนตรี
            $table->foreignId('level_id')->nullable()->constrained()->nullOnDelete();       // ระดับคอร์ส

            $table->unsignedInteger('total_sessions');   // จำนวนครั้งเรียน
            $table->unsignedInteger('duration_months');  // ระยะเวลาคอร์ส (เดือน) — ใช้คำนวณสิทธิ์ขยายเวลาอัตโนมัติ

            $table->decimal('price', 10, 2);

            $table->enum('learning_format', ['individual', 'group', 'online', 'hybrid'])->default('individual');
            $table->unsignedInteger('max_students')->default(1); // จำนวนผู้เรียนสูงสุด

            $table->boolean('allow_makeup_class')->default(true);   // สิทธิ์เรียนชดเชย
            $table->unsignedTinyInteger('emergency_leave_quota')->default(1); // โควตาลาฉุกเฉิน/คอร์ส (ปกติ 1 ครั้ง)

            $table->boolean('is_adult_flexi')->default(false); // Adult Flexi Course
            $table->boolean('is_active')->default(true);       // เปิด/ปิดการใช้งานคอร์ส

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'learning_format']);
        });

        // อาจารย์ที่สามารถสอนคอร์สนี้ได้ (many-to-many)
        Schema::create('course_teacher', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['course_id', 'teacher_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_teacher');
        Schema::dropIfExists('courses');
    }
};
