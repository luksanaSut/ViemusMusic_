<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Guardian extends Model
{
    use SoftDeletes;

    protected $fillable = ['full_name', 'phone', 'email', 'line_id', 'address', 'notes'];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_guardian')
            ->withPivot('relation', 'is_primary')->withTimestamps();
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('full_name', 'like', "%{$term}%")->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
