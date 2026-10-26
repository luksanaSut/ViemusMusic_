<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\RolePermission;
use Illuminate\Database\Seeder;

// รายการสิทธิ์ของระบบ (ชุดเดียวกับใน migration permissions/role_permissions/trial_leads)
// รันซ้ำได้ปลอดภัย — ใช้เติมข้อมูลกรณีฐานข้อมูลถูก import มาแต่ตาราง permissions ว่าง (หน้า "จัดการสิทธิ์" ไม่มีรายการ)
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // [key, label, module, is_locked, sort_order]
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
            ['trial_leads.manage', 'ผู้สนใจและทดลองเรียน', 'งานขาย', false, 9],
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

        foreach ($rows as [$key, $label, $module, $isLocked, $sortOrder]) {
            Permission::updateOrCreate(['key' => $key], [
                'label'      => $label,
                'module'     => $module,
                'is_locked'  => $isLocked,
                'sort_order' => $sortOrder,
            ]);
        }

        // สิทธิ์เริ่มต้นของ staff — ใส่เฉพาะตอนที่ยังไม่เคยตั้งค่า เพื่อไม่ทับสิทธิ์ที่แอดมินปรับไว้แล้ว
        if (RolePermission::where('role', 'staff')->exists()) {
            return;
        }

        $defaultStaffKeys = [
            'teachers.manage',
            'courses.manage',
            'students.manage',
            'student_leaves.manage',
            'guardians.manage',
            'rooms.manage',
            'sales.manage',
            'trial_leads.manage',
            'course_transfers.manage',
            'schedules.manage',
            'teacher_leaves.manage',
            'makeup_reschedule.manage',
            'products.manage',
            'store_sales.manage',
        ];

        Permission::whereIn('key', $defaultStaffKeys)->pluck('id')
            ->each(fn ($id) => RolePermission::create(['role' => 'staff', 'permission_id' => $id]));
    }
}
