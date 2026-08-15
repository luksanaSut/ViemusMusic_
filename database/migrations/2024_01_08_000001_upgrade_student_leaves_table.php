<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // เพิ่มค่า 'no_makeup' เข้า enum เดิม (emergency, normal)
        DB::statement("ALTER TABLE student_leaves MODIFY leave_type ENUM('emergency','normal','no_makeup') NOT NULL");

        Schema::table('student_leaves', function (Blueprint $table) {
            $table->foreignId('class_schedule_id')->nullable()->after('enrollment_id')->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('reason');
            $table->string('reviewed_by')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
        });
    }

    public function down(): void
    {
        Schema::table('student_leaves', function (Blueprint $table) {
            $table->dropConstrainedForeignId('class_schedule_id');
            $table->dropColumn(['status', 'reviewed_by', 'reviewed_at']);
        });
        DB::statement("ALTER TABLE student_leaves MODIFY leave_type ENUM('emergency','normal') NOT NULL");
    }
};
