<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_log_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('content_taught')->nullable();      // เนื้อหาที่สอน
            $table->text('homework')->nullable();             // การบ้าน
            $table->text('progress_notes')->nullable();       // ความก้าวหน้าของนักเรียน
            $table->text('notes')->nullable();                // หมายเหตุเพิ่มเติม
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('teaching_report_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_report_id')->constrained()->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_report_attachments');
        Schema::dropIfExists('teaching_reports');
    }
};