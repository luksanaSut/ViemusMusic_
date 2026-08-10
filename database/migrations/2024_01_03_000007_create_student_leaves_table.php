<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('enrollment_id')->constrained()->cascadeOnDelete();

            $table->enum('leave_type', ['emergency', 'normal']); // Business rule: ลาฉุกเฉิน มีโควตาจำกัดตามคอร์ส
            $table->date('leave_date');
            $table->text('reason')->nullable();

            $table->boolean('is_makeup_required')->default(true);
            $table->date('makeup_date')->nullable();
            $table->enum('makeup_status', ['pending', 'scheduled', 'completed', 'not_required'])->default('pending');

            $table->timestamps();

            $table->index(['enrollment_id', 'leave_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_leaves');
    }
};
