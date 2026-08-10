<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('equipment_type_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->string('condition')->nullable(); // เช่น ใช้งานได้, ชำรุด, ส่งซ่อม
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['room_id', 'equipment_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_equipment');
    }
};
