<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentMembership extends Model
{
    protected $fillable = [
        'student_id',
        'membership_tier_id',
        'total_spend_12m',
        'lifetime_spend',
        'renewed_at',
        'next_review_at',
    ];

    protected $casts = [
        'total_spend_12m' => 'decimal:2',
        'lifetime_spend' => 'decimal:2',
        'renewed_at' => 'datetime',
        'next_review_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function tier(): BelongsTo
    {
        return $this->belongsTo(MembershipTier::class, 'membership_tier_id');
    }

    public function isDueForReview(): bool
    {
        return $this->next_review_at !== null && now()->gte($this->next_review_at);
    }
}
