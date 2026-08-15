<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'teacher', 'student', 'guardian'])->default('admin')->after('email');
            $table->foreignId('teacher_id')->nullable()->unique()->after('role')->constrained()->nullOnDelete();
            $table->foreignId('student_id')->nullable()->unique()->after('teacher_id')->constrained()->nullOnDelete();
            $table->foreignId('guardian_id')->nullable()->unique()->after('student_id')->constrained()->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('guardian_id');
            $table->boolean('must_change_password')->default(false)->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('teacher_id');
            $table->dropConstrainedForeignId('student_id');
            $table->dropConstrainedForeignId('guardian_id');
            $table->dropColumn(['role', 'is_active', 'must_change_password']);
        });
    }
};
