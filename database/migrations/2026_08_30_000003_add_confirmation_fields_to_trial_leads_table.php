<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_leads', function (Blueprint $table) {
            $table->string('confirmation_status')->default('pending')->after('trial_result');
            $table->timestamp('guardian_confirmed_at')->nullable()->after('confirmation_status');
            $table->string('guardian_confirmed_by')->nullable()->after('guardian_confirmed_at');
            $table->timestamp('teacher_confirmed_at')->nullable()->after('guardian_confirmed_by');
            $table->string('teacher_confirmed_by')->nullable()->after('teacher_confirmed_at');
            $table->text('confirmation_notes')->nullable()->after('teacher_confirmed_by');
            $table->timestamp('checked_in_at')->nullable()->after('confirmation_notes');
            $table->string('checked_in_by')->nullable()->after('checked_in_at');
        });
    }

    public function down(): void
    {
        Schema::table('trial_leads', function (Blueprint $table) {
            $table->dropColumn([
                'confirmation_status', 'guardian_confirmed_at', 'guardian_confirmed_by',
                'teacher_confirmed_at', 'teacher_confirmed_by', 'confirmation_notes',
                'checked_in_at', 'checked_in_by',
            ]);
        });
    }
};
