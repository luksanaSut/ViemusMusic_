<?php

namespace App\Console\Commands;

use App\Models\Student;
use App\Services\LoyaltyService;
use Illuminate\Console\Command;

class ProcessLoyaltyMembership extends Command
{
    protected $signature = 'loyalty:process';

    protected $description = 'ทบทวนระดับสมาชิกทุกคน, หมดอายุแต้มที่ครบกำหนด, และแจ้งเตือนแต้มใกล้หมดอายุ';

    public function handle(LoyaltyService $loyalty): int
    {
        $reviewed = 0;
        Student::chunk(100, function ($students) use ($loyalty, &$reviewed) {
            foreach ($students as $student) {
                $loyalty->recalculateMembership($student);
                $reviewed++;
            }
        });
        $this->info("ทบทวนระดับสมาชิกแล้ว {$reviewed} คน");

        $expired = $loyalty->expireDuePoints();
        $this->info("หมดอายุแต้มแล้ว {$expired} รายการ");

        $notified = $loyalty->notifyExpiringSoon();
        $this->info("แจ้งเตือนแต้มใกล้หมดอายุแล้ว {$notified} รายการ");

        return self::SUCCESS;
    }
}
