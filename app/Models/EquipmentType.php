<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EquipmentType extends Model
{
    protected $fillable = ['name'];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_equipment')
            ->withPivot('id', 'quantity', 'condition', 'note')->withTimestamps();
    }
}
