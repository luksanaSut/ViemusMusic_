<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_code')->unique();
            $table->string('name');
            $table->string('location')->nullable(); // เช่น ชั้น 2, ตึก A
            $table->unsignedInteger('capacity');     // Business rule: จำกัดจำนวนคนที่จองห้องได้
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);

            // ปิดปรับปรุงห้องชั่วคราว
            $table->boolean('is_under_maintenance')->default(false);
            $table->text('maintenance_reason')->nullable();
            $table->date('maintenance_from')->nullable();
            $table->date('maintenance_to')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'is_under_maintenance']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
