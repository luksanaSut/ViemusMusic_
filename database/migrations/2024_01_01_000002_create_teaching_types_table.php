<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ประเภทอาจารย์: สอนประจำ / Accompaniment / Workshop
        Schema::create('teaching_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // สอนประจำ, Accompaniment, Workshop
            $table->string('code')->unique();  // regular, accompaniment, workshop
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_types');
    }
};
