<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // อาจารย์ 1 คน สอนได้หลายเครื่องดนตรี
        Schema::create('teacher_instrument', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false); // เครื่องดนตรีหลัก
            $table->timestamps();

            $table->unique(['teacher_id', 'instrument_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_instrument');
    }
};
