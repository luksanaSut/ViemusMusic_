<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_sales', function (Blueprint $table) {
            // promotion_id/promotion_code = คูปองที่ลูกค้า/แอดมินกรอกโค้ดเอง
            $table->foreignId('promotion_id')->nullable()->after('student_id')->constrained('promotions')->nullOnDelete();
            $table->string('promotion_code')->nullable()->after('promotion_id');
            // auto_promotion_id = โปรโมชันอัตโนมัติที่ระบบจับคู่ให้ (ซ้อนกับคูปองได้)
            $table->foreignId('auto_promotion_id')->nullable()->after('promotion_code')->constrained('promotions')->nullOnDelete();
            // total_amount ยังคงหมายถึงยอดรวมก่อนหักส่วนลดเหมือนเดิม (ไม่แตะโค้ดตัดสต็อก/คำนวณเดิม)
            $table->decimal('discount_amount', 10, 2)->default(0)->after('total_amount');
            $table->decimal('auto_discount_amount', 10, 2)->default(0)->after('discount_amount');
            $table->decimal('net_payable', 10, 2)->nullable()->after('auto_discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('store_sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('promotion_id');
            $table->dropConstrainedForeignId('auto_promotion_id');
            $table->dropColumn(['promotion_code', 'discount_amount', 'auto_discount_amount', 'net_payable']);
        });
    }
};
