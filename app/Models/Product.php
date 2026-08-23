<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'category_id',
        'price',
        'cost_price',
        'image_path',
        'stock_quantity',
        'reorder_level',
        'status',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->orderByDesc('created_at');
    }
    public function promotions(): BelongsToMany
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product')->withTimestamps();
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")->orWhere('sku', 'like', "%{$term}%");
        });
    }

    public function scopeCategory($query, $categoryId)
    {
        return $categoryId ? $query->where('category_id', $categoryId) : $query;
    }

    public function statusLabel(): string
    {
        return $this->status === 'active' ? 'พร้อมขาย' : 'ปิดการขาย';
    }

    public function statusBadgeClass(): string
    {
        return $this->status === 'active' ? 'text-bg-success' : 'text-bg-secondary';
    }

    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->reorder_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->stock_quantity <= 0;
    }

    public static function generateSku(): string
    {
        $last = static::withTrashed()->orderByDesc('id')->first();
        $next = $last ? $last->id + 1 : 1;
        return 'PRD-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}