<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('homework_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_report_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1); // ส่งใหม่ = version เพิ่มขึ้น
            $table->text('student_note')->nullable(); // นักเรียนเขียนอธิบายงานที่ส่ง

            $table->enum('status', ['submitted', 'approved', 'needs_revision'])->default('submitted');
            $table->text('feedback')->nullable();
            $table->string('reviewed_by')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->string('submitted_by')->nullable();
            $table->timestamps();

            $table->index(['teaching_report_id', 'version']);
        });

        Schema::create('homework_submission_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('homework_submission_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homework_submission_files');
        Schema::dropIfExists('homework_submissions');
    }
};