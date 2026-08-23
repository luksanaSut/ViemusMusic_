<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Promotion extends Model
{
    protected $fillable = [
        'code',
        'name',
        'scope',
        'discount_type',
        'discount_value',
        'min_spend',
        'max_uses',
        'per_customer_limit',
        'used_count',
        'valid_from',
        'valid_to',
        'applies_to_all',
        'is_active',
    ];

    protected $casts = [
        'applies_to_all' => 'boolean',
        'is_active'      => 'boolean',
        'valid_from'     => 'date',
        'valid_to'       => 'date',
        'discount_value' => 'decimal:2',
        'min_spend'      => 'decimal:2',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'promotion_course')->withTimestamps();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promotion_product')->withTimestamps();
    }

    public function usages(): HasMany
    {
        return $this->hasMany(PromotionUsage::class);
    }

    public function isCoupon(): bool
    {
        return !is_null($this->code);
    }

    public function isAutoPromotion(): bool
    {
        return is_null($this->code);
    }

    public function isCurrentlyValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->valid_from && now()->lt($this->valid_from)) return false;
        if ($this->valid_to && now()->gt($this->valid_to)) return false;
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    // ใช้ได้กับเป้าหมายประเภท $type ('course'|'product') กลุ่ม id ใน $ids หรือไม่ (มีตัวใดตัวหนึ่งเข้าเงื่อนไขก็พอ)
    public function appliesToAnyTarget(string $type, array $ids): bool
    {
        if ($this->scope !== 'both' && $this->scope !== $type) return false;
        if ($this->applies_to_all) return true;
        if (empty($ids)) return false;

        return $type === 'course'
            ? $this->courses()->whereIn('courses.id', $ids)->exists()
            : $this->products()->whereIn('products.id', $ids)->exists();
    }

    public function appliesToTarget(string $type, int $id): bool
    {
        return $this->appliesToAnyTarget($type, [$id]);
    }

    public function discountLabel(): string
    {
        return match ($this->discount_type) {
            'percent'   => number_format($this->discount_value, 0) . '%',
            'fixed'     => number_format($this->discount_value, 2) . ' บาท',
            'spend_get' => 'ซื้อครบ ฿' . number_format($this->min_spend, 0) . ' ลด ฿' . number_format($this->discount_value, 0),
            default     => number_format($this->discount_value, 2),
        };
    }

    public function remainingGlobalUses(): ?int
    {
        return $this->max_uses === null ? null : max(0, $this->max_uses - $this->used_count);
    }

    public function usesByCustomer(?int $studentId, ?string $buyerIdentifier): int
    {
        if (!$studentId && !$buyerIdentifier) return 0;

        return $this->usages()
            ->where(function ($q) use ($studentId, $buyerIdentifier) {
                if ($studentId) $q->orWhere('student_id', $studentId);
                if ($buyerIdentifier) $q->orWhere('buyer_identifier', $buyerIdentifier);
            })
            ->count();
    }

    public function reachedPerCustomerLimit(?int $studentId, ?string $buyerIdentifier): bool
    {
        if ($this->per_customer_limit === null) return false;

        return $this->usesByCustomer($studentId, $buyerIdentifier) >= $this->per_customer_limit;
    }
}
