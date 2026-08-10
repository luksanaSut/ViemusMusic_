<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('discount_type', ['percent', 'fixed'])->default('percent');
            $table->decimal('discount_value', 10, 2);
            $table->unsignedInteger('max_uses')->nullable();  // null = ไม่จำกัดจำนวนครั้ง
            $table->unsignedInteger('used_count')->default(0);
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
            $table->boolean('applies_to_all_courses')->default(true); // true = ใช้ได้ทุกคอร์ส
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // คอร์สที่คูปองนี้ใช้ได้ (มีผลเฉพาะตอน applies_to_all_courses = false)
        Schema::create('course_coupon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['course_id', 'coupon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_coupon');
        Schema::dropIfExists('coupons');
    }
};
