<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // เรทค่าจ้าง: กำหนดแยกตามอาจารย์ / ประเภทการสอน / เครื่องดนตรี ได้อย่างอิสระ
        Schema::create('teacher_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teaching_type_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('instrument_id')->nullable()->constrained()->nullOnDelete();

            // รูปแบบค่าจ้าง: ต่อชั่วโมง / ต่อครั้ง(คาบ) / เหมาต่อเดือน
            $table->enum('rate_type', ['per_hour', 'per_session', 'monthly_fixed'])->default('per_hour');
            $table->decimal('rate_amount', 10, 2);

            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['teacher_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_rates');
    }
};
