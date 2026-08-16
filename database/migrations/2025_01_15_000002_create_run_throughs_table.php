<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('run_throughs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->text('description')->nullable(); // รายละเอียดแบบฝึกหัด

            // บันทึกหลังฝึกซ้อม
            $table->enum('practice_result', ['excellent', 'good', 'needs_practice'])->nullable();
            $table->text('areas_to_improve')->nullable(); // Business rule: สิ่งที่ต้องฝึกเพิ่มเติม
            $table->text('teacher_comment')->nullable();
            $table->timestamp('result_recorded_at')->nullable();

            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->index('enrollment_id');
        });

        Schema::create('run_through_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('run_through_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('run_through_attachments');
        Schema::dropIfExists('run_throughs');
    }
};