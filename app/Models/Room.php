<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Room extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'room_code',
        'name',
        'location',
        'capacity',
        'description',
        'is_active',
        'is_under_maintenance',
        'maintenance_reason',
        'maintenance_from',
        'maintenance_to',
    ];

    protected $casts = [
        'is_active'             => 'boolean',
        'is_under_maintenance'  => 'boolean',
        'maintenance_from'      => 'date',
        'maintenance_to'        => 'date',
    ];

    // ===== Relationships =====
    public function equipment(): BelongsToMany
    {
        return $this->belongsToMany(EquipmentType::class, 'room_equipment')
            ->withPivot('id', 'quantity', 'condition', 'note')->withTimestamps();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }

    // ===== Scopes =====
    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('room_code', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%");
        });
    }

    public function scopeMinCapacity($query, ?int $min)
    {
        return $min ? $query->where('capacity', '>=', $min) : $query;
    }

    // ===== Helpers =====
    // ตรวจห้องว่างในช่วงเวลาที่กำหนด (ไม่ทับซ้อนกับการจองที่ยืนยันแล้ว และไม่ได้ปิดปรับปรุงอยู่)
    public function isAvailable(string $date, string $startTime, string $endTime, ?int $excludeBookingId = null): bool
    {
        if ($this->is_under_maintenance) {
            return false;
        }

        $overlap = $this->bookings()
            ->where('booking_date', $date)
            ->where('status', 'confirmed')
            ->when($excludeBookingId, fn($q) => $q->where('id', '!=', $excludeBookingId))
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('start_time', '<', $endTime)->where('end_time', '>', $startTime);
            })
            ->exists();

        return !$overlap;
    }

    public function statusLabel(): string
    {
        if ($this->is_under_maintenance) return 'ปิดปรับปรุง';
        return $this->is_active ? 'ใช้งานได้' : 'ปิดใช้งาน';
    }

    public function statusBadgeClass(): string
    {
        if ($this->is_under_maintenance) return 'text-bg-warning';
        return $this->is_active ? 'text-bg-success' : 'text-bg-secondary';
    }

    public function todayBookingsCount(): int
    {
        return $this->bookings()
            ->where('booking_date', now()->toDateString())
            ->where('status', 'confirmed')
            ->count();
    }
}
