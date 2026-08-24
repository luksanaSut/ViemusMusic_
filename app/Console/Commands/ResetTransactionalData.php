<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

class ResetTransactionalData extends Command
{
    protected $signature = 'system:reset-transactional-data
        {--dry-run : แสดงจำนวนแถวที่จะถูกลบโดยไม่ลบจริง}
        {--no-backup : ข้ามการสำรองข้อมูลอัตโนมัติ (ไม่แนะนำ)}
        {--force : ข้ามการยืนยันแบบพิมพ์ยืนยัน (ใช้กับสคริปต์อัตโนมัติเท่านั้น)}';

    protected $description = 'ลบข้อมูลธุรกรรม/ประวัติทั้งหมด ยกเว้นข้อมูลนักเรียน อาจารย์ ผู้ปกครอง ห้องเรียน คอร์สเรียน (และตารางอ้างอิง/ผู้ใช้งาน/สิทธิ์)';

    /**
     * ตารางที่จะถูกลบข้อมูลทั้งหมด — ทุกอย่างยกเว้นนักเรียน/อาจารย์/ผู้ปกครอง/ห้องเรียน/คอร์สเรียน,
     * ตารางอ้างอิง/จับคู่ของ 5 เอนทิตีนี้, users, permissions/role_permissions, และตาราง infra ของ Laravel
     */
    private const TABLES_TO_WIPE = [
        'app_notifications',
        'audit_logs',
        'class_schedules',
        'course_evaluation_items',
        'course_evaluations',
        'course_transfers',
        'enrollments',
        'evaluation_categories',
        'exam_results',
        'expenses',
        'homework_submission_files',
        'homework_submissions',
        'makeup_requests',
        'membership_tiers',
        'payments',
        'payroll_run_items',
        'payroll_runs',
        'product_categories',
        'products',
        'promotion_course',
        'promotion_product',
        'promotion_usages',
        'promotions',
        'reschedule_requests',
        'run_through_attachments',
        'run_throughs',
        'sale_orders',
        'stock_movements',
        'store_sale_items',
        'store_sales',
        'student_credit_transactions',
        'student_leaves',
        'student_memberships',
        'student_point_transactions',
        'tax_invoices',
        'teacher_leaves',
        'teaching_evidences',
        'teaching_logs',
        'teaching_report_attachments',
        'teaching_reports',
        'teaching_sessions',
        'transport_compensations',
    ];

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        $this->warn('ตารางที่จะถูกลบข้อมูลทั้งหมด (' . count(self::TABLES_TO_WIPE) . ' ตาราง):');
        $this->line(implode(', ', self::TABLES_TO_WIPE));
        $this->newLine();

        $counts = [];
        foreach (self::TABLES_TO_WIPE as $table) {
            $counts[$table] = DB::table($table)->count();
        }

        $this->table(['ตาราง', 'จำนวนแถวปัจจุบัน'], collect($counts)->map(fn($c, $t) => [$t, $c])->values());

        $total = array_sum($counts);
        $this->newLine();
        $this->warn("รวมทั้งหมด {$total} แถวที่จะถูกลบ");

        if ($dryRun) {
            $this->info('Dry-run: ไม่มีการลบข้อมูลจริง');

            return self::SUCCESS;
        }

        if (! $this->option('force')) {
            $this->error('การกระทำนี้ลบข้อมูลถาวรและกู้คืนไม่ได้ (นอกจากกู้จากไฟล์สำรอง)');
            $confirmed = $this->ask('พิมพ์ RESET เพื่อยืนยันการลบข้อมูลข้างต้นทั้งหมด');
            if ($confirmed !== 'RESET') {
                $this->info('ยกเลิกการทำงาน — ไม่มีข้อมูลถูกลบ');

                return self::FAILURE;
            }
        }

        if (! $this->option('no-backup')) {
            $backupPath = $this->backupDatabase();
            if ($backupPath === null) {
                $this->error('สำรองข้อมูลไม่สำเร็จ — หยุดการทำงานเพื่อความปลอดภัย (ใช้ --no-backup เพื่อข้ามขั้นตอนนี้หากตั้งใจ)');

                return self::FAILURE;
            }
            $this->info("สำรองข้อมูลไว้ที่: {$backupPath}");
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (self::TABLES_TO_WIPE as $table) {
            DB::table($table)->truncate();
            $this->line("ลบข้อมูลตาราง {$table} แล้ว");
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->newLine();
        $this->info('ลบข้อมูลธุรกรรม/ประวัติทั้งหมดเรียบร้อย — ข้อมูลนักเรียน อาจารย์ ผู้ปกครอง ห้องเรียน คอร์สเรียน และผู้ใช้งานยังคงอยู่ครบ');

        return self::SUCCESS;
    }

    private function backupDatabase(): ?string
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        $dir = storage_path('app/backups');
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $filename = 'pre-reset-' . now()->format('Ymd_His') . '.sql';
        $path = $dir . '/' . $filename;

        $process = new Process([
            '/Applications/XAMPP/xamppfiles/bin/mysqldump',
            '-h',
            $config['host'],
            '-P',
            (string) $config['port'],
            '-u',
            $config['username'],
            '--result-file=' . $path,
            $config['database'],
        ], null, ['MYSQL_PWD' => $config['password']]);

        $process->setTimeout(600);
        $process->run();

        if (! $process->isSuccessful() || ! is_file($path) || filesize($path) === 0) {
            $this->error($process->getErrorOutput());

            return null;
        }

        return $path;
    }
}
