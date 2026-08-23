<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // สินค้าที่โปรโมชัน/คูปองนี้ใช้ได้ (มีผลเฉพาะตอน applies_to_all = false และ scope เป็น product/both)
        Schema::create('promotion_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['promotion_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_product');
    }
};
