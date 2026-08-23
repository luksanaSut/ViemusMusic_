<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_sales', function (Blueprint $table) {
            $table->unsignedInteger('points_used')->default(0)->after('net_payable');
            $table->decimal('points_discount_amount', 10, 2)->default(0)->after('points_used');
            $table->decimal('credit_used', 10, 2)->default(0)->after('points_discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('store_sales', function (Blueprint $table) {
            $table->dropColumn(['points_used', 'points_discount_amount', 'credit_used']);
        });
    }
};
