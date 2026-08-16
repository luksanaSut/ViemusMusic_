<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('course_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->unique()->constrained()->cascadeOnDelete();
            $table->text('overall_comment')->nullable();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('evaluated_by')->nullable();
            $table->timestamp('evaluated_at')->nullable();
            $table->timestamps();
        });

        Schema::create('course_evaluation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_evaluation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluation_category_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('score');
            $table->text('comment')->nullable();
            $table->timestamps();

            // ตั้งชื่อ constraint เองแบบสั้น เพราะชื่ออัตโนมัติของ Laravel ยาวเกิน 64 ตัวอักษรที่ MySQL รองรับ
            $table->unique(['course_evaluation_id', 'evaluation_category_id'], 'course_eval_items_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_evaluation_items');
        Schema::dropIfExists('course_evaluations');
        Schema::dropIfExists('evaluation_categories');
    }
};