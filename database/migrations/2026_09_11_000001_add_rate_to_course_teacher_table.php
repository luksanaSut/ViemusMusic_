<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // เรทค่าสอนเฉพาะคอร์สนี้ของอาจารย์แต่ละคน (แยกจากเรทหลักในตาราง teacher_rates)
        Schema::table('course_teacher', function (Blueprint $table) {
            $table->enum('rate_type', ['per_hour', 'per_session', 'monthly_fixed', 'percentage'])
                ->nullable()->after('teacher_id');
            $table->decimal('rate_amount', 10, 2)->nullable()->after('rate_type');
        });
    }

    public function down(): void
    {
        Schema::table('course_teacher', function (Blueprint $table) {
            $table->dropColumn(['rate_type', 'rate_amount']);
        });
    }
};
