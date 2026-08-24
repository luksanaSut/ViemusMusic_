<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','staff','teacher','student','guardian') NOT NULL DEFAULT 'admin'");
    }

    public function down(): void
    {
        // หมายเหตุ: ต้องไม่มีแถว role='staff' หลงเหลืออยู่ก่อน rollback ไม่งั้น ALTER จะล้มเหลว
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin','teacher','student','guardian') NOT NULL DEFAULT 'admin'");
    }
};
