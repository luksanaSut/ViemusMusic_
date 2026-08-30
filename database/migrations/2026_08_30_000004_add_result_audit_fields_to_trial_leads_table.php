<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trial_leads', function (Blueprint $table) {
            $table->timestamp('result_recorded_at')->nullable()->after('teacher_feedback');
            $table->string('result_recorded_by')->nullable()->after('result_recorded_at');
        });
    }

    public function down(): void
    {
        Schema::table('trial_leads', fn (Blueprint $table) => $table->dropColumn(['result_recorded_at', 'result_recorded_by']));
    }
};
