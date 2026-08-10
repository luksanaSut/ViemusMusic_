<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ค่ารถ: อาจกำหนดเป็นเหมาต่อวัน หรือ ต่อระยะทาง (บาท/กม.)
        Schema::create('teacher_transport_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->enum('fee_type', ['fixed_per_day', 'per_km'])->default('fixed_per_day');
            $table->decimal('fee_amount', 10, 2);
            $table->date('effective_from')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_transport_fees');
    }
};
