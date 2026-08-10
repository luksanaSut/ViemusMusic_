<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();

            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();

            $table->string('branch')->nullable();
            $table->enum('delivery_mode', ['onsite', 'online', 'hybrid'])->nullable();
            $table->tinyInteger('preferred_day_of_week')->nullable(); // 0=อาทิตย์..6=เสาร์
            $table->time('preferred_start_time')->nullable();
            $table->time('preferred_end_time')->nullable();

            $table->decimal('base_price', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0); // เผื่อ Promotion/Coupon เฟส 2
            $table->decimal('credit_used', 10, 2)->default(0);      // เผื่อใช้เครดิต เฟส 2
            $table->decimal('vat_rate', 5, 2)->default(7.00);
            $table->decimal('vat_amount', 10, 2);
            $table->decimal('total_amount', 10, 2);

            $table->string('payment_proof_path')->nullable();
            $table->enum('payment_method', ['cash', 'transfer', 'credit_card', 'other'])->nullable();

            $table->enum('status', ['pending_payment', 'paid', 'cancelled'])->default('pending_payment');

            $table->foreignId('enrollment_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained()->nullOnDelete();

            $table->text('notes')->nullable();
            $table->string('sold_by')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_orders');
    }
};
