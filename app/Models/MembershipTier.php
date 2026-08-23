<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MembershipTier extends Model
{
    protected $fillable = [
        'name',
        'sort_order',
        'min_spend',
        'benefits',
        'badge_color',
        'is_active',
    ];

    protected $casts = [
        'min_spend' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function memberships(): HasMany
    {
        return $this->hasMany(StudentMembership::class);
    }

    public function benefitsList(): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", (string) $this->benefits))));
    }

    public function badgeClass(): string
    {
        return 'text-bg-' . $this->badge_color;
    }
}
