<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // แยกสิทธิ์เป็น 2 ระดับ: มีแถวใน role_permissions = ดูได้, can_edit = true = แก้ไขได้ด้วย
    // แถวเดิมทั้งหมดตั้งเป็นแก้ไขได้ (default true) เพื่อให้สิทธิ์ของ staff ที่ใช้อยู่ไม่เปลี่ยน
    public function up(): void
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->boolean('can_edit')->default(true)->after('permission_id');
        });
    }

    public function down(): void
    {
        Schema::table('role_permissions', function (Blueprint $table) {
            $table->dropColumn('can_edit');
        });
    }
};
