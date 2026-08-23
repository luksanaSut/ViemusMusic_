<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            // promotion_id/promotion_code/discount_amount = คูปองที่ลูกค้ากรอกโค้ดเอง (ความหมายเดิม แค่เปลี่ยนชื่อ)
            $table->renameColumn('coupon_id', 'promotion_id');
            $table->renameColumn('coupon_code', 'promotion_code');
        });

        Schema::table('sale_orders', function (Blueprint $table) {
            // auto_promotion_id/auto_discount_amount = โปรโมชันอัตโนมัติที่ระบบจับคู่ให้ (แยกจากคูปอง เพราะซ้อนกันได้)
            $table->foreignId('auto_promotion_id')->nullable()->after('promotion_code')->constrained('promotions')->nullOnDelete();
            $table->decimal('auto_discount_amount', 10, 2)->default(0)->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('auto_promotion_id');
            $table->dropColumn('auto_discount_amount');
        });

        Schema::table('sale_orders', function (Blueprint $table) {
            $table->renameColumn('promotion_id', 'coupon_id');
            $table->renameColumn('promotion_code', 'coupon_code');
        });
    }
};
