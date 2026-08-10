<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            // รูปแบบการเรียน: ที่โรงเรียน / ออนไลน์ / ไฮบริด
            $table->enum('delivery_mode', ['onsite', 'online', 'hybrid'])->default('onsite')->after('class_type');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('delivery_mode');
        });
    }
};
