<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // เก็บอาจารย์ที่เลือกไว้ตอนสมัคร/ซื้อคอร์ส ให้หน้าจัดตารางเรียนดึงมาใช้ต่อได้อัตโนมัติ
            $table->foreignId('teacher_id')->nullable()->after('course_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('teacher_id');
        });
    }
};
