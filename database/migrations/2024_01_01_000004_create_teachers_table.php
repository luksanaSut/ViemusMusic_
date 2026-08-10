<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('teacher_code')->unique();     // รหัสอาจารย์ เช่น T0001
            $table->string('full_name');
            $table->string('nickname')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('line_id')->nullable();
            $table->text('address')->nullable();
            $table->string('photo_path')->nullable();

            // สถานะการจ้างงาน: Freelance หรือ Full-time
            $table->enum('employment_type', ['full_time', 'freelance'])->default('freelance');

            $table->text('bio')->nullable();
            $table->boolean('is_active')->default(true);
            $table->date('start_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'employment_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
