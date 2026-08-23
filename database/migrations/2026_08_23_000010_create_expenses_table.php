<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['course', 'product_cost', 'rent', 'staff', 'maintenance', 'other']);
            $table->date('expense_date');
            $table->decimal('amount', 12, 2);
            $table->string('title');
            $table->text('note')->nullable();
            $table->string('recorded_by')->nullable();
            $table->timestamps();

            $table->index(['category', 'expense_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
