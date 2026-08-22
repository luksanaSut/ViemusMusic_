<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE store_sales MODIFY status ENUM('pending_payment','completed','cancelled') NOT NULL DEFAULT 'completed'");

        Schema::table('store_sales', function (Blueprint $table) {
            $table->string('payment_proof_path')->nullable()->after('payment_method');
            $table->string('payment_reference')->nullable()->after('payment_proof_path');
            $table->timestamp('confirmed_at')->nullable()->after('payment_reference');
            $table->foreignId('ordered_by_user_id')->nullable()->after('sold_by')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('store_sales', function (Blueprint $table) {
            $table->dropConstrainedForeignId('ordered_by_user_id');
            $table->dropColumn(['payment_proof_path', 'payment_reference', 'confirmed_at']);
        });
        DB::statement("ALTER TABLE store_sales MODIFY status ENUM('completed','cancelled') NOT NULL DEFAULT 'completed'");
    }
};