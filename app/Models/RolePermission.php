<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    protected $fillable = [
        'role',
        'permission_id',
        'can_edit',
    ];

    protected $casts = [
        'can_edit' => 'boolean',
    ];

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    // มีแถว = ดูได้; ถ้าระบุ $requireEdit ต้องเป็น can_edit ด้วย
    public static function isGranted(string $role, string $permissionKey, bool $requireEdit = false): bool
    {
        return static::whereHas('permission', fn ($q) => $q->where('key', $permissionKey))
            ->where('role', $role)
            ->when($requireEdit, fn ($q) => $q->where('can_edit', true))
            ->exists();
    }
}
