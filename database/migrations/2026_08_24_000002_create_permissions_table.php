<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('module');
            $table->boolean('is_locked')->default(false); // true = ล็อก admin-only เสมอ ไม่ให้ toggle ผ่าน UI
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        $now = now();
        $rows = [
            ['teachers.manage', 'จัดการอาจารย์', 'อาจารย์', false, 1],
            ['courses.manage', 'จัดการคอร์สเรียน', 'วิชาการ', false, 2],
            ['promotions.manage', 'โปรโมชัน/คูปอง', 'งานขาย', false, 3],
            ['membership.manage', 'ระดับสมาชิก', 'งานขาย', false, 4],
            ['students.manage', 'จัดการนักเรียน', 'นักเรียน', false, 5],
            ['student_leaves.manage', 'ลาเรียนนักเรียน', 'นักเรียน', false, 6],
            ['guardians.manage', 'ผู้ปกครอง', 'นักเรียน', false, 7],
            ['rooms.manage', 'ห้องเรียน', 'ตารางเรียน', false, 8],
            ['sales.manage', 'ขายคอร์สเรียน', 'งานขาย', false, 9],
            ['course_transfers.manage', 'เปลี่ยนคอร์ส', 'งานขาย', false, 10],
            ['schedules.manage', 'ตารางเรียน', 'ตารางเรียน', false, 11],
            ['teacher_leaves.manage', 'อนุมัติลาอาจารย์', 'อาจารย์', false, 12],
            ['users.manage', 'จัดการผู้ใช้งาน', 'ระบบ', true, 13],
            ['makeup_reschedule.manage', 'เรียนชดเชย/สลับคลาส', 'ตารางเรียน', false, 14],
            ['payroll.manage', 'เงินเดือนอาจารย์', 'การเงิน', false, 15],
            ['transport_fees.manage', 'ค่ารถอาจารย์', 'การเงิน', false, 16],
            ['finance.manage', 'การเงิน', 'การเงิน', false, 17],
            ['reports.view', 'รายงาน', 'รายงาน', false, 18],
            ['products.manage', 'สินค้า/สต็อก', 'Music Store', false, 19],
            ['store_sales.manage', 'ขายสินค้า', 'Music Store', false, 20],
            ['role_permissions.manage', 'จัดการสิทธิ์', 'ระบบ', true, 21],
            ['audit_logs.view', 'ดูประวัติการใช้งาน', 'ระบบ', false, 22],
        ];

        DB::table('permissions')->insert(array_map(fn ($r) => [
            'key'        => $r[0],
            'label'      => $r[1],
            'module'     => $r[2],
            'is_locked'  => $r[3],
            'sort_order' => $r[4],
            'created_at' => $now,
            'updated_at' => $now,
        ], $rows));
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
