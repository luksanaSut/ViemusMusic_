<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_point_transactions', function (Blueprint $table) {
            $table->foreignId('store_sale_id')->nullable()->after('sale_order_id')->constrained()->nullOnDelete();
            $table->timestamp('expires_at')->nullable()->after('reason'); // เฉพาะ batch ที่ให้แต้ม (earn/adjustment บวก)
            $table->integer('remaining_points')->nullable()->after('expires_at'); // แต้มคงเหลือของก้อนนี้ (FIFO)
            $table->timestamp('expiring_notified_at')->nullable()->after('remaining_points'); // กันแจ้งเตือนซ้ำ
        });

        DB::statement("ALTER TABLE student_point_transactions MODIFY type ENUM('earn','redeem','adjustment','expire') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE student_point_transactions MODIFY type ENUM('earn','redeem','adjustment') NOT NULL");

        Schema::table('student_point_transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_sale_id');
            $table->dropColumn(['expires_at', 'remaining_points', 'expiring_notified_at']);
        });
    }
};
