<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // บันทึกทุกครั้งที่มีการใช้โปรโมชัน/คูปองจริง (ตอนยืนยันชำระเงินสำเร็จเท่านั้น)
        // ใช้ทั้งเป็น audit log และเช็คโควตาต่อลูกค้า (per_customer_limit)
        Schema::create('promotion_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sale_order_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('store_sale_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->string('buyer_identifier')->nullable(); // fallback สำหรับขายหน้าร้านแบบไม่ผูกนักเรียน
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->timestamp('used_at')->useCurrent();
            $table->timestamps();

            $table->index(['promotion_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_usages');
    }
};
