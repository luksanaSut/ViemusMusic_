<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('membership_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->decimal('min_spend', 12, 2)->default(0); // ยอดใช้จ่ายสะสม 12 เดือนขั้นต่ำที่ต้องถึง
            $table->text('benefits')->nullable(); // สิทธิประโยชน์ (ข้อความอิสระ 1 บรรทัด = 1 สิทธิ์)
            $table->string('badge_color', 20)->default('secondary');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('membership_tiers');
    }
};
