<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    protected $fillable = [
        'role',
        'permission_id',
    ];

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public static function isGranted(string $role, string $permissionKey): bool
    {
        return static::whereHas('permission', fn ($q) => $q->where('key', $permissionKey))
            ->where('role', $role)
            ->exists();
    }
}
