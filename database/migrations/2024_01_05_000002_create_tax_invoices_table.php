<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_order_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_no')->unique();
            $table->enum('invoice_type', ['receipt', 'tax_invoice'])->default('receipt');

            $table->boolean('is_company')->default(false);
            $table->string('buyer_name');
            $table->string('buyer_tax_id', 20)->nullable(); // บังคับกรอกเมื่อ is_company = true
            $table->text('buyer_address')->nullable();
            $table->string('buyer_phone', 20)->nullable();

            $table->decimal('subtotal', 10, 2);   // ราคาก่อน VAT
            $table->decimal('vat_rate', 5, 2);
            $table->decimal('vat_amount', 10, 2);
            $table->decimal('total_amount', 10, 2); // รวม VAT

            $table->date('issued_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_invoices');
    }
};
