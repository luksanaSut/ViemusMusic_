<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'category',
        'expense_date',
        'amount',
        'title',
        'note',
        'recorded_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'course'       => 'คอร์สเรียน',
            'product_cost' => 'ค่าซื้อสินค้า',
            'rent'         => 'ค่าเช่า',
            'staff'        => 'ค่าพนักงาน',
            'maintenance'  => 'ค่าซ่อมบำรุง',
            'other'        => 'ค่าใช้จ่ายอื่นๆ',
            default        => $this->category,
        };
    }

    public function categoryIcon(): string
    {
        return match ($this->category) {
            'course'       => 'bi-journal-bookmark',
            'product_cost' => 'bi-box-seam',
            'rent'         => 'bi-building',
            'staff'        => 'bi-people',
            'maintenance'  => 'bi-tools',
            'other'        => 'bi-three-dots',
            default        => 'bi-receipt',
        };
    }

    public function scopeCategory($query, ?string $category)
    {
        return $category ? $query->where('category', $category) : $query;
    }

    public function scopeBetween($query, $start, $end)
    {
        return $query->whereBetween('expense_date', [$start, $end]);
    }
}
