<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // อาจารย์ 1 คน อาจสอนได้หลายประเภท (ประจำ / Accompaniment / Workshop)
        Schema::create('teacher_teaching_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teaching_type_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['teacher_id', 'teaching_type_id'], 'teacher_type_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_teaching_type');
    }
};
