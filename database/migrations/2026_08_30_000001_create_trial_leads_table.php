<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trial_leads', function (Blueprint $table) {
            $table->id();
            $table->string('lead_no')->unique();
            $table->string('student_name');
            $table->string('nickname', 50)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('guardian_name')->nullable();
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('line_id')->nullable();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();
            $table->string('interest')->nullable();
            $table->string('preferred_schedule')->nullable();
            $table->date('trial_date')->nullable();
            $table->time('trial_start_time')->nullable();
            $table->time('trial_end_time')->nullable();
            $table->enum('delivery_mode', ['onsite', 'online'])->default('onsite');
            $table->decimal('trial_fee', 10, 2)->default(0);
            $table->enum('payment_status', ['unpaid', 'paid', 'waived', 'refunded'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->enum('status', ['new', 'contacted', 'scheduled', 'completed', 'converted', 'lost'])->default('new');
            $table->enum('trial_result', ['interested', 'considering', 'not_interested', 'no_show'])->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->date('next_follow_up_date')->nullable();
            $table->foreignId('converted_student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->timestamp('converted_at')->nullable();
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
            $table->index(['status', 'next_follow_up_date']);
            $table->index(['trial_date', 'teacher_id']);
        });

        DB::table('permissions')->insert([
            'key' => 'trial_leads.manage',
            'label' => 'ผู้สนใจและทดลองเรียน',
            'module' => 'งานขาย',
            'is_locked' => false,
            'sort_order' => 9,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $permissionId = DB::table('permissions')->where('key', 'trial_leads.manage')->value('id');
        if ($permissionId) {
            DB::table('role_permissions')->insertOrIgnore([
                'role' => 'staff',
                'permission_id' => $permissionId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        $permissionId = DB::table('permissions')->where('key', 'trial_leads.manage')->value('id');
        if ($permissionId) DB::table('role_permissions')->where('permission_id', $permissionId)->delete();
        DB::table('permissions')->where('key', 'trial_leads.manage')->delete();
        Schema::dropIfExists('trial_leads');
    }
};
