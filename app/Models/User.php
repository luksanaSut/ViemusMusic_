<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'teacher_id',
        'student_id',
        'guardian_id',
        'is_active',
        'must_change_password',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'              => 'hashed',
            'is_active'             => 'boolean',
            'must_change_password'  => 'boolean',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function guardian(): BelongsTo
    {
        return $this->belongsTo(Guardian::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }
    public function isGuardian(): bool
    {
        return $this->role === 'guardian';
    }

    // admin มีสิทธิ์ทุกโมดูลเสมอ; staff ต้องได้รับสิทธิ์ผ่านตาราง role_permissions ก่อน
    public function hasModulePermission(string $key): bool
    {
        if ($this->isAdmin()) return true;

        return RolePermission::isGranted($this->role, $key);
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'admin'    => 'ผู้ดูแลระบบ',
            'staff'    => 'เจ้าหน้าที่',
            'teacher'  => 'อาจารย์',
            'student'  => 'นักเรียน',
            'guardian' => 'ผู้ปกครอง',
            default    => $this->role,
        };
    }

    public function roleBadgeClass(): string
    {
        return match ($this->role) {
            'admin'    => 'text-bg-dark',
            'staff'    => 'text-bg-info',
            'teacher'  => 'text-bg-primary',
            'student'  => 'text-bg-success',
            'guardian' => 'text-bg-warning',
            default    => 'text-bg-light',
        };
    }

    // ชื่อที่ใช้แสดงในระบบ อิงตามข้อมูลที่ผูกไว้ ถ้ามี
    public function displayName(): string
    {
        return match ($this->role) {
            'teacher'  => $this->teacher?->full_name ?? $this->name,
            'student'  => $this->student?->full_name ?? $this->name,
            'guardian' => $this->guardian?->full_name ?? $this->name,
            default    => $this->name,
        };
    }
}
