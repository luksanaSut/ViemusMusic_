<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_tier_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_spend_12m', 12, 2)->default(0); // ยอดใช้จ่าย 12 เดือนล่าสุด ณ ครั้งที่คำนวณ
            $table->decimal('lifetime_spend', 12, 2)->default(0); // แสดงผลอย่างเดียว
            $table->timestamp('renewed_at')->nullable(); // คำนวณ/ทบทวนระดับล่าสุดเมื่อไหร่
            $table->timestamp('next_review_at')->nullable(); // ทบทวนครั้งถัดไป
            $table->timestamps();

            $table->unique('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_memberships');
    }
};
