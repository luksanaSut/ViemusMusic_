<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('store_sales', function (Blueprint $table) {
            $table->enum('delivery_method', ['pickup', 'delivery'])->default('pickup')->after('total_amount');
            $table->string('delivery_recipient_name')->nullable()->after('delivery_method');
            $table->string('delivery_phone')->nullable()->after('delivery_recipient_name');
            $table->text('delivery_address')->nullable()->after('delivery_phone');
            $table->string('delivery_tracking_no')->nullable()->after('delivery_address');
            $table->enum('delivery_status', ['not_applicable', 'preparing', 'shipped', 'ready_for_pickup', 'picked_up', 'delivered'])
                ->default('not_applicable')->after('delivery_tracking_no');
        });
    }

    public function down(): void
    {
        Schema::table('store_sales', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_method',
                'delivery_recipient_name',
                'delivery_phone',
                'delivery_address',
                'delivery_tracking_no',
                'delivery_status',
            ]);
        });
    }
};