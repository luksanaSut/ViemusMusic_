<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('instrument_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('exam_board', ['abrsm', 'trinity']); // ABRSM / Trinity College London
            $table->string('grade'); // เช่น Grade 1, Grade 5, ARSM
            $table->date('exam_date');
            $table->enum('result', ['distinction', 'merit', 'pass', 'fail'])->nullable();
            $table->string('score')->nullable(); // เช่น 130/150
            $table->string('certificate_no')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'exam_board']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_results');
    }
};
