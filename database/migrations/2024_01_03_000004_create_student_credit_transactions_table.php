<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_credit_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['topup', 'use', 'refund', 'adjustment']);
            $table->decimal('amount', 10, 2); // topup/refund เป็นบวก, use เป็นลบ
            $table->decimal('balance_after', 10, 2);
            $table->string('reason')->nullable();
            $table->timestamps();

            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_credit_transactions');
    }
};
