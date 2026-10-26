<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MasterDataSeeder::class,   // เครื่องดนตรี, ประเภทอาจารย์, ระดับ (ต้องมาก่อน)
            TeacherSeeder::class,      // ข้อมูลตัวอย่างอาจารย์
            AdminUserSeeder::class,    // จัดการข้อมูลผู้ใช้งานระบบ
            PermissionSeeder::class,   // รายการสิทธิ์ + สิทธิ์เริ่มต้นของ staff (หน้า "จัดการสิทธิ์")
        ]);
    }
}