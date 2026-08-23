<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\MembershipTier;
use App\Models\SaleOrder;
use App\Models\Student;
use App\Models\StoreSale;
use App\Models\StudentMembership;
use App\Models\StudentPointTransaction;

class LoyaltyService
{
    public function earnPoints(Student $student, int $points, ?SaleOrder $order = null, ?StoreSale $sale = null, string $reason = ''): void
    {
        if ($points <= 0) return;

        $newBalance = $student->pointBalance() + $points;

        StudentPointTransaction::create([
            'student_id'       => $student->id,
            'sale_order_id'    => $order?->id,
            'store_sale_id'    => $sale?->id,
            'type'             => 'earn',
            'points'           => $points,
            'balance_after'    => $newBalance,
            'reason'           => $reason,
            'expires_at'       => now()->addYear(),
            'remaining_points' => $points,
        ]);

        $this->recalculateMembership($student);
    }

    public function redeemPoints(Student $student, int $points, ?SaleOrder $order = null, ?StoreSale $sale = null, string $reason = ''): void
    {
        if ($points <= 0) return;

        $newBalance = $student->pointBalance() - $points;

        StudentPointTransaction::create([
            'student_id'    => $student->id,
            'sale_order_id' => $order?->id,
            'store_sale_id' => $sale?->id,
            'type'          => 'redeem',
            'points'        => -$points,
            'balance_after' => $newBalance,
            'reason'        => $reason,
        ]);

        $this->consumeFifo($student, $points);
    }

    // ปรับยอดมือโดยแอดมิน (ไม่ผูกกับคำสั่งซื้อ) — บวก = สร้าง batch ใหม่, ลบ = หักแบบ FIFO เหมือน redeem
    public function adjustPoints(Student $student, int $delta, string $reason): void
    {
        if ($delta === 0) return;

        $newBalance = $student->pointBalance() + $delta;

        StudentPointTransaction::create([
            'student_id'       => $student->id,
            'type'             => 'adjustment',
            'points'           => $delta,
            'balance_after'    => $newBalance,
            'reason'           => $reason,
            'expires_at'       => $delta > 0 ? now()->addYear() : null,
            'remaining_points' => $delta > 0 ? $delta : null,
        ]);

        if ($delta < 0) {
            $this->consumeFifo($student, abs($delta));
        }

        if ($delta > 0) {
            $this->recalculateMembership($student);
        }
    }

    // คืนแต้มที่ใช้ไปของรายการที่ถูกยกเลิก + ตัดแต้มที่เคยได้จากรายการนั้นทิ้ง (ถ้ายังไม่ถูกใช้ต่อ)
    public function reversePurchasePoints(Student $student, ?SaleOrder $order = null, ?StoreSale $sale = null): void
    {
        $redeemTx = StudentPointTransaction::where('student_id', $student->id)
            ->where('type', 'redeem')
            ->when($order, fn ($q) => $q->where('sale_order_id', $order->id))
            ->when($sale, fn ($q) => $q->where('store_sale_id', $sale->id))
            ->first();

        if ($redeemTx) {
            $this->adjustPoints($student, abs($redeemTx->points), 'คืนแต้มจากการยกเลิกรายการ');
        }

        $earnTx = StudentPointTransaction::where('student_id', $student->id)
            ->where('type', 'earn')
            ->when($order, fn ($q) => $q->where('sale_order_id', $order->id))
            ->when($sale, fn ($q) => $q->where('store_sale_id', $sale->id))
            ->first();

        if ($earnTx && $earnTx->remaining_points > 0) {
            // หักผ่าน adjustPoints (FIFO ทั่วไป) เพื่อไม่ให้หักซ้ำสองครั้ง — best-effort ไม่จำเป็นต้องตรงก้อนเป๊ะถ้าถูกใช้ต่อไปแล้วบางส่วน
            $this->adjustPoints($student, -$earnTx->remaining_points, 'ตัดแต้มที่เคยได้จากรายการที่ถูกยกเลิก');
        }
    }

    public function expireDuePoints(): int
    {
        $count = 0;

        StudentPointTransaction::whereIn('type', ['earn', 'adjustment'])
            ->where('remaining_points', '>', 0)
            ->where('expires_at', '<=', now())
            ->with('student')
            ->chunkById(100, function ($batches) use (&$count) {
                foreach ($batches as $batch) {
                    $student = $batch->student;
                    if (!$student) continue;

                    $expired = $batch->remaining_points;
                    $newBalance = $student->pointBalance() - $expired;

                    StudentPointTransaction::create([
                        'student_id'    => $student->id,
                        'type'          => 'expire',
                        'points'        => -$expired,
                        'balance_after' => $newBalance,
                        'reason'        => 'แต้มหมดอายุ',
                    ]);

                    $batch->update(['remaining_points' => 0]);

                    AppNotification::notifyStudentAndGuardians(
                        $student,
                        'แต้มสะสมหมดอายุ',
                        "แต้มสะสมจำนวน {$expired} แต้มของคุณหมดอายุแล้ว",
                    );

                    $count++;
                }
            });

        return $count;
    }

    public function notifyExpiringSoon(int $withinDays = 30): int
    {
        $count = 0;

        StudentPointTransaction::whereIn('type', ['earn', 'adjustment'])
            ->where('remaining_points', '>', 0)
            ->whereNull('expiring_notified_at')
            ->whereBetween('expires_at', [now(), now()->addDays($withinDays)])
            ->with('student')
            ->chunkById(100, function ($batches) use (&$count) {
                foreach ($batches as $batch) {
                    $student = $batch->student;
                    if (!$student) continue;

                    AppNotification::notifyStudentAndGuardians(
                        $student,
                        'แต้มสะสมใกล้หมดอายุ',
                        "แต้มสะสมจำนวน {$batch->remaining_points} แต้มของคุณจะหมดอายุวันที่ {$batch->expires_at->format('d/m/Y')}",
                    );

                    $batch->update(['expiring_notified_at' => now()]);
                    $count++;
                }
            });

        return $count;
    }

    public function recalculateMembership(Student $student): StudentMembership
    {
        $since12m = now()->subMonths(12);

        $spend12m = (float) SaleOrder::where('student_id', $student->id)
            ->where('status', 'paid')
            ->where('updated_at', '>=', $since12m)
            ->sum('net_payable')
            + (float) StoreSale::where('student_id', $student->id)
                ->where('status', 'completed')
                ->where('updated_at', '>=', $since12m)
                ->sum('net_payable');

        $lifetimeSpend = (float) SaleOrder::where('student_id', $student->id)
            ->where('status', 'paid')
            ->sum('net_payable')
            + (float) StoreSale::where('student_id', $student->id)
                ->where('status', 'completed')
                ->sum('net_payable');

        $tier = MembershipTier::where('is_active', true)
            ->where('min_spend', '<=', $spend12m)
            ->orderByDesc('min_spend')
            ->first();

        $membership = $student->membership ?? new StudentMembership(['student_id' => $student->id]);
        $previousTierId = $membership->membership_tier_id;

        $membership->fill([
            'membership_tier_id' => $tier?->id,
            'total_spend_12m'    => $spend12m,
            'lifetime_spend'     => $lifetimeSpend,
            'renewed_at'         => now(),
            'next_review_at'     => now()->addYear(),
        ]);
        $membership->save();

        if ($tier && $tier->id !== $previousTierId) {
            AppNotification::notifyStudentAndGuardians(
                $student,
                'ระดับสมาชิกอัปเดต',
                "ระดับสมาชิกของคุณเปลี่ยนเป็น {$tier->name} แล้ว",
            );
        }

        return $membership;
    }

    private function consumeFifo(Student $student, int $amount): void
    {
        $remaining = $amount;

        foreach ($student->activePointBatches() as $batch) {
            if ($remaining <= 0) break;

            $take = min($batch->remaining_points, $remaining);
            $batch->decrement('remaining_points', $take);
            $remaining -= $take;
        }
    }
}
