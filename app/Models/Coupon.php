<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'name',
        'discount_type',
        'discount_value',
        'max_uses',
        'used_count',
        'valid_from',
        'valid_to',
        'applies_to_all_courses',
        'is_active',
    ];

    protected $casts = [
        'applies_to_all_courses' => 'boolean',
        'is_active'              => 'boolean',
        'valid_from'             => 'date',
        'valid_to'               => 'date',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_coupon')->withTimestamps();
    }

    public function isCurrentlyValid(): bool
    {
        if (!$this->is_active) return false;
        if ($this->valid_from && now()->lt($this->valid_from)) return false;
        if ($this->valid_to && now()->gt($this->valid_to)) return false;
        if ($this->max_uses !== null && $this->used_count >= $this->max_uses) return false;
        return true;
    }

    public function discountLabel(): string
    {
        return $this->discount_type === 'percent'
            ? number_format($this->discount_value, 0) . '%'
            : number_format($this->discount_value, 2) . ' บาท';
    }
}
