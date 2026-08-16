<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_log_id')->constrained()->cascadeOnDelete();
            $table->enum('file_type', ['image', 'video', 'document']);
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable(); // bytes
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('uploaded_by_name')->nullable(); // เก็บชื่อไว้ตรงๆ กันกรณีบัญชีถูกลบภายหลัง
            $table->timestamps();

            $table->index(['teaching_log_id', 'file_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_evidences');
    }
};