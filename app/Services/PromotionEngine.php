<?php

namespace App\Services;

use App\Models\Promotion;
use App\Models\PromotionUsage;

class PromotionEngine
{
    // หาโปรโมชันอัตโนมัติ (ไม่มีโค้ด) ที่ดีที่สุดที่ใช้ได้กับตะกร้านี้ ณ ตอนนี้
    public function findBestAutoPromotion(string $scope, array $targetIds, float $baseTotal, ?int $studentId, ?string $buyerIdentifier): ?Promotion
    {
        $candidates = Promotion::whereNull('code')
            ->where('is_active', true)
            ->get()
            ->filter(fn (Promotion $p) => $this->isEligible($p, $scope, $targetIds, $baseTotal, $studentId, $buyerIdentifier));

        if ($candidates->isEmpty()) return null;

        return $candidates
            ->sortByDesc(fn (Promotion $p) => $this->calculateDiscount($p, $baseTotal, $baseTotal))
            ->first();
    }

    // ตรวจสอบโค้ดคูปองที่ลูกค้ากรอกเอง
    public function resolveCoupon(string $code, string $scope, array $targetIds, float $baseTotal, ?int $studentId, ?string $buyerIdentifier): array
    {
        $promotion = Promotion::whereNotNull('code')
            ->where('code', strtoupper(trim($code)))
            ->first();

        if (!$promotion) {
            return ['promotion' => null, 'error' => 'โค้ดคูปองไม่ถูกต้อง หมดอายุ หรือใช้ครบจำนวนสิทธิ์แล้ว'];
        }

        if (!$this->isEligible($promotion, $scope, $targetIds, $baseTotal, $studentId, $buyerIdentifier)) {
            return ['promotion' => null, 'error' => $this->eligibilityErrorMessage($promotion, $scope, $targetIds, $baseTotal, $studentId, $buyerIdentifier)];
        }

        return ['promotion' => $promotion, 'error' => null];
    }

    public function calculateDiscount(Promotion $promotion, float $running, float $baseTotal): float
    {
        return match ($promotion->discount_type) {
            'percent'   => round($running * (float) $promotion->discount_value / 100, 2),
            'fixed'     => min((float) $promotion->discount_value, $running),
            'spend_get' => $baseTotal >= (float) $promotion->min_spend ? min((float) $promotion->discount_value, $running) : 0,
            default     => 0,
        };
    }

    // Pipeline เต็ม: auto-promo ก่อน (คิดจาก baseTotal) แล้วค่อยคูปอง (คิดจาก running total ที่เหลือ) — ซ้อนกันได้
    public function applyToCart(string $scope, array $targetIds, float $baseTotal, ?string $couponCode, ?int $studentId, ?string $buyerIdentifier): array
    {
        $running = $baseTotal;

        $autoPromotion = $this->findBestAutoPromotion($scope, $targetIds, $baseTotal, $studentId, $buyerIdentifier);
        $autoDiscount = 0;
        if ($autoPromotion) {
            $autoDiscount = $this->calculateDiscount($autoPromotion, $running, $baseTotal);
            $running -= $autoDiscount;
        }

        $coupon = null;
        $couponDiscount = 0;
        if (!empty($couponCode)) {
            $result = $this->resolveCoupon($couponCode, $scope, $targetIds, $baseTotal, $studentId, $buyerIdentifier);
            if ($result['error']) {
                return [
                    'auto_promotion' => null, 'auto_discount' => 0,
                    'coupon' => null, 'coupon_discount' => 0,
                    'total_discount' => 0, 'net_payable' => $baseTotal,
                    'error' => $result['error'],
                ];
            }
            $coupon = $result['promotion'];
            $couponDiscount = $this->calculateDiscount($coupon, $running, $baseTotal);
            $running -= $couponDiscount;
        }

        return [
            'auto_promotion'  => $autoPromotion,
            'auto_discount'   => $autoDiscount,
            'coupon'          => $coupon,
            'coupon_discount' => $couponDiscount,
            'total_discount'  => round($autoDiscount + $couponDiscount, 2),
            'net_payable'     => max(0, round($running, 2)),
            'error'           => null,
        ];
    }

    // สำหรับกรณีมีเป้าหมายเดียว (เช่น SaleOrder ที่มีคอร์สเดียว)
    public function applyToOrder(string $scope, int $targetId, float $baseTotal, ?string $couponCode, ?int $studentId, ?string $buyerIdentifier): array
    {
        return $this->applyToCart($scope, [$targetId], $baseTotal, $couponCode, $studentId, $buyerIdentifier);
    }

    // บันทึกการใช้งานจริง (เรียกครั้งเดียวตอนยืนยันชำระเงินสำเร็จ) — เพิ่ม used_count + log
    public function recordUsage(array $applied, array $context): void
    {
        $saleOrderId = $context['sale_order_id'] ?? null;
        $storeSaleId = $context['store_sale_id'] ?? null;
        $studentId = $context['student_id'] ?? null;
        $buyerIdentifier = $context['buyer_identifier'] ?? null;

        foreach (['auto_promotion' => 'auto_discount', 'coupon' => 'coupon_discount'] as $promotionKey => $discountKey) {
            /** @var Promotion|null $promotion */
            $promotion = $applied[$promotionKey] ?? null;
            $discount = $applied[$discountKey] ?? 0;

            if (!$promotion || $discount <= 0) continue;

            PromotionUsage::create([
                'promotion_id'      => $promotion->id,
                'sale_order_id'     => $saleOrderId,
                'store_sale_id'     => $storeSaleId,
                'student_id'        => $studentId,
                'buyer_identifier'  => $buyerIdentifier,
                'discount_amount'   => $discount,
                'used_at'           => now(),
            ]);

            $promotion->increment('used_count');
        }
    }

    // ยกเลิกการใช้งาน (ตอนยกเลิกออเดอร์/การขาย) — ลบ log + ลด used_count กลับ
    public function voidUsage(array $context): void
    {
        $query = PromotionUsage::query();

        if (!empty($context['sale_order_id'])) {
            $query->where('sale_order_id', $context['sale_order_id']);
        } elseif (!empty($context['store_sale_id'])) {
            $query->where('store_sale_id', $context['store_sale_id']);
        } else {
            return;
        }

        $usages = $query->get();
        foreach ($usages as $usage) {
            $usage->promotion?->decrement('used_count');
            $usage->delete();
        }
    }

    private function isEligible(Promotion $promotion, string $scope, array $targetIds, float $baseTotal, ?int $studentId, ?string $buyerIdentifier): bool
    {
        if (!$promotion->isCurrentlyValid()) return false;
        if (!$promotion->appliesToAnyTarget($scope, $targetIds)) return false;
        if ($promotion->discount_type === 'spend_get' && $baseTotal < (float) $promotion->min_spend) return false;
        if ($promotion->reachedPerCustomerLimit($studentId, $buyerIdentifier)) return false;

        return true;
    }

    private function eligibilityErrorMessage(Promotion $promotion, string $scope, array $targetIds, float $baseTotal, ?int $studentId, ?string $buyerIdentifier): string
    {
        if (!$promotion->isCurrentlyValid()) {
            return 'โค้ดคูปองไม่ถูกต้อง หมดอายุ หรือใช้ครบจำนวนสิทธิ์แล้ว';
        }
        if (!$promotion->appliesToAnyTarget($scope, $targetIds)) {
            return 'คูปองนี้ใช้ไม่ได้กับรายการที่เลือก';
        }
        if ($promotion->discount_type === 'spend_get' && $baseTotal < (float) $promotion->min_spend) {
            return 'ยอดสั่งซื้อยังไม่ถึง ฿' . number_format((float) $promotion->min_spend, 0) . ' ตามเงื่อนไขของคูปองนี้';
        }
        if ($promotion->reachedPerCustomerLimit($studentId, $buyerIdentifier)) {
            return 'คุณใช้คูปองนี้ครบจำนวนสิทธิ์ที่กำหนดต่อคนแล้ว';
        }

        return 'ไม่สามารถใช้คูปองนี้ได้';
    }
}
