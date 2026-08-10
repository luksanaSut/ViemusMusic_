<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->foreignId('coupon_id')->nullable()->after('course_id')->constrained()->nullOnDelete();
            $table->string('coupon_code')->nullable()->after('coupon_id');
            $table->unsignedInteger('points_used')->default(0)->after('credit_used');
            $table->decimal('points_discount_amount', 10, 2)->default(0)->after('points_used');
            $table->decimal('net_payable', 10, 2)->nullable()->after('total_amount');
        });

        // เพิ่ม promptpay เข้าไปใน enum ช่องทางชำระเงิน (ต้องมี doctrine/dbal ติดตั้งไว้แล้ว)
        DB::statement("ALTER TABLE sale_orders MODIFY payment_method ENUM('cash','transfer','credit_card','promptpay','other') NULL");
    }

    public function down(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('coupon_id');
            $table->dropColumn(['coupon_code', 'points_used', 'points_discount_amount', 'net_payable']);
        });
        DB::statement("ALTER TABLE sale_orders MODIFY payment_method ENUM('cash','transfer','credit_card','other') NULL");
    }
};
