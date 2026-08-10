<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->enum('structure_type', ['regular', 'special'])->default('regular')->after('course_code');
            $table->enum('class_type', ['private', 'group', 'special_activity'])->default('private')->after('structure_type');
            $table->enum('activity_type', ['camp', 'workshop', 'master_class'])->nullable()->after('class_type');

            $table->unsignedInteger('days_count')->nullable()->after('total_sessions');   // จำนวนวัน (แบบพิเศษ)
            $table->decimal('hours_per_day', 4, 1)->nullable()->after('days_count');       // ชั่วโมง/วัน (แบบพิเศษ)
            $table->date('course_start_date')->nullable()->after('duration_months');
            $table->date('course_end_date')->nullable()->after('course_start_date');

            $table->string('image_path')->nullable()->after('description');

            // แบบปกติต้องกรอก / แบบพิเศษไม่บังคับ จึงต้อง nullable ทั้งคู่
            $table->unsignedInteger('total_sessions')->nullable()->change();
            $table->unsignedInteger('duration_months')->nullable()->change();

            // Private = ไม่จำกัดจำนวน จึงต้อง nullable
            $table->unsignedInteger('max_students')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'structure_type',
                'class_type',
                'activity_type',
                'days_count',
                'hours_per_day',
                'course_start_date',
                'course_end_date',
                'image_path',
            ]);
            $table->unsignedInteger('total_sessions')->nullable(false)->change();
            $table->unsignedInteger('duration_months')->nullable(false)->change();
            $table->unsignedInteger('max_students')->nullable(false)->default(1)->change();
        });
    }
};
