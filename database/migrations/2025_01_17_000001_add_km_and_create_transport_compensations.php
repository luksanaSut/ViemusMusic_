<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teaching_sessions', function (Blueprint $table) {
            $table->decimal('km_traveled', 8, 2)->nullable()->after('transport_fee_applied');
        });

        Schema::create('transport_compensations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->date('compensation_date');
            $table->decimal('amount', 10, 2);
            $table->string('reason');
            $table->string('created_by')->nullable();
            $table->timestamps();

            $table->index(['teacher_id', 'compensation_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transport_compensations');
        Schema::table('teaching_sessions', function (Blueprint $table) {
            $table->dropColumn('km_traveled');
        });
    }
};