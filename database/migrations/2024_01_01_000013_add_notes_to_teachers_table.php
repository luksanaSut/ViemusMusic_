<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // หมายเหตุเพิ่มเติม (สำหรับแอดมิน) แยกจาก bio ที่เป็นประวัติย่อของอาจารย์
            $table->text('notes')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('notes');
        });
    }
};
