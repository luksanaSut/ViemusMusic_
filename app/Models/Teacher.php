<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'teacher_code',
        'full_name',
        'nickname',
        'email',
        'phone',
        'line_id',
        'address',
        'photo_path',
        'employment_type',
        'branch',
        'bio',
        'notes',
        'is_active',
        'start_date',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'start_date' => 'date',
    ];

    // ===== Relationships =====
    public function instruments(): BelongsToMany
    {
        return $this->belongsToMany(Instrument::class, 'teacher_instrument')
            ->withPivot('is_primary')->withTimestamps();
    }

    public function teachingTypes(): BelongsToMany
    {
        return $this->belongsToMany(TeachingType::class, 'teacher_teaching_type')->withTimestamps();
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'teacher_level')->withTimestamps();
    }

    public function rates(): HasMany
    {
        return $this->hasMany(TeacherRate::class);
    }

    public function activeRates(): HasMany
    {
        return $this->rates()->where('is_active', true);
    }

    public function transportFees(): HasMany
    {
        return $this->hasMany(TeacherTransportFee::class);
    }

    public function activeTransportFee()
    {
        return $this->hasOne(TeacherTransportFee::class)->where('is_active', true)->latestOfMany('effective_from');
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(TeacherAvailability::class);
    }

    public function leaves(): HasMany
    {
        return $this->hasMany(TeacherLeave::class);
    }

    public function teachingSessions(): HasMany
    {
        return $this->hasMany(TeachingSession::class);
    }

    // ===== Scopes =====
    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")
                ->orWhere('nickname', 'like', "%{$term}%")
                ->orWhere('teacher_code', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%");
        });
    }

    public function scopeEmploymentType($query, ?string $type)
    {
        return $type ? $query->where('employment_type', $type) : $query;
    }

    public function scopeTeachingType($query, ?int $teachingTypeId)
    {
        return $teachingTypeId
            ? $query->whereHas('teachingTypes', fn($q) => $q->where('teaching_types.id', $teachingTypeId))
            : $query;
    }

    public function scopeInstrument($query, ?int $instrumentId)
    {
        return $instrumentId
            ? $query->whereHas('instruments', fn($q) => $q->where('instruments.id', $instrumentId))
            : $query;
    }

    // ===== Helpers =====
    public function totalHours(?string $from = null, ?string $to = null): float
    {
        $q = $this->teachingSessions()->where('status', 'completed');
        if ($from) $q->whereDate('session_date', '>=', $from);
        if ($to)   $q->whereDate('session_date', '<=', $to);
        return (float) $q->sum('hours');
    }

    public function totalIncome(?string $from = null, ?string $to = null): float
    {
        $q = $this->teachingSessions()->where('status', 'completed');
        if ($from) $q->whereDate('session_date', '>=', $from);
        if ($to)   $q->whereDate('session_date', '<=', $to);
        return (float) $q->sum('income_amount');
    }

    public function studentCount(): int
    {
        return $this->teachingSessions()
            ->whereNotNull('student_name')
            ->distinct('student_name')
            ->count('student_name');
    }

    public function hasClassToday(): bool
    {
        return $this->teachingSessions()
            ->whereDate('session_date', now()->toDateString())
            ->whereIn('status', ['scheduled', 'completed'])
            ->exists();
    }

    public function employmentTypeLabel(): string
    {
        return $this->employment_type === 'full_time' ? 'Full-time' : 'Freelance';
    }

    public function initials(): string
    {
        $name = $this->nickname ?: $this->full_name;
        return mb_substr($name, 0, 1);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
