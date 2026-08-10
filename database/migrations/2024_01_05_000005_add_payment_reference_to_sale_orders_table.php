<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            // เลขอ้างอิงการทำรายการ ใช้กับบัตรเครดิต/เดบิต (Ref. no / Auth code / เลข 4 หลักท้ายบัตร)
            $table->string('payment_reference')->nullable()->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('sale_orders', function (Blueprint $table) {
            $table->dropColumn('payment_reference');
        });
    }
};
