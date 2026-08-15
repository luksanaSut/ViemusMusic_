<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('recipient_role', ['admin', 'teacher']);
            $table->unsignedBigInteger('recipient_id')->nullable(); // null = แจ้งทุกคนใน role นั้น (เฉพาะ admin)
            $table->string('title');
            $table->text('message');
            $table->string('link_url')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index(['recipient_role', 'recipient_id', 'is_read']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_notifications');
    }
};
