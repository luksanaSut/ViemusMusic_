<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // สาขา/ศูนย์ที่อาจารย์ประจำอยู่ เช่น Cloud 11, Astra Academy
            $table->string('branch')->nullable()->after('employment_type');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn('branch');
        });
    }
};
