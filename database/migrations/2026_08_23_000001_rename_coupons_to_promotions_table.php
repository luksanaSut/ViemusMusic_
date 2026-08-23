<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('coupons', 'promotions');
        Schema::rename('course_coupon', 'promotion_course');

        Schema::table('promotion_course', function (Blueprint $table) {
            $table->renameColumn('coupon_id', 'promotion_id');
        });

        Schema::table('promotions', function (Blueprint $table) {
            $table->string('code')->nullable()->change(); // NULL = auto-applied promotion
            $table->enum('scope', ['course', 'product', 'both'])->default('course')->after('name');
            $table->renameColumn('applies_to_all_courses', 'applies_to_all');
            $table->decimal('min_spend', 10, 2)->nullable()->after('discount_value');
            $table->unsignedInteger('per_customer_limit')->nullable()->after('max_uses');
        });

        DB::statement("ALTER TABLE promotions MODIFY discount_type ENUM('percent','fixed','spend_get') NOT NULL DEFAULT 'percent'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE promotions MODIFY discount_type ENUM('percent','fixed') NOT NULL DEFAULT 'percent'");

        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn(['scope', 'min_spend', 'per_customer_limit']);
            $table->renameColumn('applies_to_all', 'applies_to_all_courses');
            $table->string('code')->nullable(false)->change();
        });

        Schema::table('promotion_course', function (Blueprint $table) {
            $table->renameColumn('promotion_id', 'coupon_id');
        });

        Schema::rename('promotion_course', 'course_coupon');
        Schema::rename('promotions', 'coupons');
    }
};
