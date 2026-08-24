<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('role'); // 'staff' — รองรับขยายในอนาคต แต่ปัจจุบันมีความหมายเฉพาะ staff
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['role', 'permission_id']);
        });

        // สิทธิ์เริ่มต้นของ staff: งานประจำวัน ไม่แตะระบบ/การเงิน/โปรโมชัน/รายงาน/สิทธิ์
        $defaultStaffKeys = [
            'teachers.manage',
            'courses.manage',
            'students.manage',
            'student_leaves.manage',
            'guardians.manage',
            'rooms.manage',
            'sales.manage',
            'course_transfers.manage',
            'schedules.manage',
            'teacher_leaves.manage',
            'makeup_reschedule.manage',
            'products.manage',
            'store_sales.manage',
        ];

        $now = now();
        $permissionIds = DB::table('permissions')->whereIn('key', $defaultStaffKeys)->pluck('id');

        DB::table('role_permissions')->insert(
            $permissionIds->map(fn ($id) => [
                'role'          => 'staff',
                'permission_id' => $id,
                'created_at'    => $now,
                'updated_at'    => $now,
            ])->all()
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
    }
};
