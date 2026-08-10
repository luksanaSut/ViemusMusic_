<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_code',
        'name',
        'description',
        'image_path',
        'structure_type',
        'class_type',
        'delivery_mode',
        'activity_type',
        'instrument_id',
        'level_id',
        'total_sessions',
        'days_count',
        'hours_per_day',
        'duration_months',
        'course_start_date',
        'course_end_date',
        'price',
        'max_students',
        'allow_makeup_class',
        'emergency_leave_quota',
        'is_adult_flexi',
        'is_active',
    ];

    protected $casts = [
        'allow_makeup_class' => 'boolean',
        'is_adult_flexi'     => 'boolean',
        'is_active'          => 'boolean',
        'price'              => 'decimal:2',
        'course_start_date'  => 'date',
        'course_end_date'    => 'date',
    ];

    // ===== Relationships =====
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'course_teacher')->withTimestamps();
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class, 'course_coupon')->withTimestamps();
    }

    // ===== Scopes =====
    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")->orWhere('course_code', 'like', "%{$term}%");
        });
    }

    public function scopeInstrument($query, ?int $instrumentId)
    {
        return $instrumentId ? $query->where('instrument_id', $instrumentId) : $query;
    }

    public function scopeLevel($query, ?int $levelId)
    {
        return $levelId ? $query->where('level_id', $levelId) : $query;
    }

    public function scopeClassType($query, ?string $type)
    {
        return $type ? $query->where('class_type', $type) : $query;
    }

    // ===== Business rule: สิทธิ์ขยายเวลาคอร์สอัตโนมัติ (เฉพาะแบบปกติ) =====
    public function maxExtensionMonths(): int
    {
        return match ((int) $this->duration_months) {
            3       => 1,
            6       => 2,
            12      => 0,
            default => 0,
        };
    }

    public function structureTypeLabel(): string
    {
        return $this->structure_type === 'special' ? 'แบบพิเศษ' : 'แบบปกติ';
    }

    public function classTypeLabel(): string
    {
        return match ($this->class_type) {
            'private'          => 'Private',
            'group'            => 'Group',
            'special_activity' => 'Special Activity',
            default            => $this->class_type,
        };
    }

    public function deliveryModeLabel(): string
    {
        return match ($this->delivery_mode) {
            'onsite' => 'ที่โรงเรียน',
            'online' => 'ออนไลน์',
            'hybrid' => 'ไฮบริด',
            default  => $this->delivery_mode,
        };
    }

    public function activityTypeLabel(): ?string
    {
        return match ($this->activity_type) {
            'camp'         => 'Camp',
            'workshop'     => 'Workshop',
            'master_class' => 'Master Class',
            default        => null,
        };
    }

    // ข้อความอธิบายกฎจำนวนผู้เรียน ใช้แสดงเป็น hint ในฟอร์ม/หน้ารายการ
    public function capacityHint(): string
    {
        return match ($this->class_type) {
            'private'          => 'Private = ไม่จำกัด/ไม่มีเต็ม',
            'group'            => 'Group กำหนดได้มากกว่า 1',
            'special_activity' => 'เต็มตามจำนวนที่กำหนดของกิจกรรม',
            default            => '',
        };
    }

    public function maxStudentsLabel(): string
    {
        return $this->class_type === 'private' ? 'ไม่จำกัด' : ($this->max_students ?? '-');
    }
}
