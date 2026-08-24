<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'method',
        'path',
        'route_name',
        'status_code',
        'ip_address',
        'meta',
    ];

    protected $casts = [
        'meta'       => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('path', 'like', "%{$term}%")
                ->orWhere('route_name', 'like', "%{$term}%")
                ->orWhere('user_name', 'like', "%{$term}%");
        });
    }

    public function scopeForUser($query, ?int $userId)
    {
        return $userId ? $query->where('user_id', $userId) : $query;
    }

    public function scopeBetween($query, ?string $from, ?string $to)
    {
        if ($from) $query->where('created_at', '>=', $from);
        if ($to) $query->where('created_at', '<=', $to);

        return $query;
    }
}
