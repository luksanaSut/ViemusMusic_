<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable(); // snapshot กันชื่อหายถ้าลบ user ทิ้ง
            $table->string('user_role')->nullable();
            $table->string('method', 10);
            $table->string('path');
            $table->string('route_name')->nullable();
            $table->unsignedSmallInteger('status_code')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->json('meta')->nullable(); // request input (ตัดฟิลด์รหัสผ่านออก)
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'created_at']);
            $table->index('route_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
