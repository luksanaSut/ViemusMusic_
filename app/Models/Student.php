<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_code',
        'full_name',
        'nickname',
        'date_of_birth',
        'gender',
        'phone',
        'email',
        'line_id',
        'address',
        'photo_path',
        'status',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    // ===== Relationships =====
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function creditTransactions(): HasMany
    {
        return $this->hasMany(StudentCreditTransaction::class);
    }
    public function pointTransactions(): HasMany
    {
        return $this->hasMany(StudentPointTransaction::class);
    }
    public function skillLevels(): HasMany
    {
        return $this->hasMany(StudentSkillLevel::class);
    }
    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }
    public function leaves(): HasMany
    {
        return $this->hasMany(StudentLeave::class);
    }
    public function saleOrders(): HasMany
    {
        return $this->hasMany(SaleOrder::class);
    }

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
            ->withPivot('relation', 'is_primary')->withTimestamps();
    }

    public function primaryGuardian(): ?Guardian
    {
        return $this->guardians->firstWhere('pivot.is_primary', true) ?? $this->guardians->first();
    }

    // ===== Scopes =====
    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
                ->orWhere('nickname', 'like', "%{$term}%")
                ->orWhere('student_code', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    // ===== Helpers: สถานะ =====
    public function statusLabel(): string
    {
        return match ($this->status) {
            'active'    => 'กำลังเรียน',
            'paused'    => 'พักเรียน',
            'cancelled' => 'ยกเลิกเรียน',
            default     => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'active'    => 'text-bg-success',
            'paused'    => 'text-bg-warning',
            'cancelled' => 'text-bg-secondary',
            default     => 'text-bg-light',
        };
    }

    public function initials(): string
    {
        $name = $this->nickname ?: $this->full_name;
        return mb_substr($name, 0, 1);
    }

    // ===== Helpers: เครดิต =====
    public function creditBalance(): float
    {
        return (float) ($this->creditTransactions()->latest('id')->value('balance_after') ?? 0);
    }

    // ===== Helpers: แต้มสะสม =====
    public function pointBalance(): int
    {
        return (int) ($this->pointTransactions()->latest('id')->value('balance_after') ?? 0);
    }

    // อัตราแลกแต้ม: 10 แต้ม = 1 บาท, แลกได้สูงสุด 20% ของยอดที่ต้องชำระ ณ ขณะนั้น
    public function maxPointsRedeemableValue(float $capBase): float
    {
        $pointsValue = floor($this->pointBalance() / 10);
        $cap = round($capBase * 0.20, 2);
        return max(0, min($pointsValue, $cap));
    }

    // ===== Helpers: การชำระเงิน =====
    public function hasOverduePayment(): bool
    {
        return $this->payments()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->where(function ($q) {
                $q->where('status', 'overdue')->orWhere('due_date', '<', now()->toDateString());
            })
            ->exists();
    }

    public function overduePaymentsCount(): int
    {
        return $this->payments()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->where(function ($q) {
                $q->where('status', 'overdue')->orWhere('due_date', '<', now()->toDateString());
            })
            ->count();
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
