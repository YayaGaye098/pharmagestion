<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'dosage',
        'form',
        'packaging_unit',
        'category_id',
        'stock_quantity',
        'min_threshold',
        'unit_price',
        'purchase_price',
        'expiration_date',
        'status',
    ];

    protected $casts = [
        'expiration_date' => 'date',
        'unit_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    // Helper status badge calculation
    public function getComputedStatusAttribute()
    {
        return $this->stockStatusFor();
    }

    public function stockStatusFor(?int $quantity = null): string
    {
        $stockQuantity = $quantity ?? (int) $this->stock_quantity;

        if ($stockQuantity <= 0) {
            return 'rupture';
        }
        if ($stockQuantity <= $this->min_threshold) {
            return 'faible';
        }

        return 'ok';
    }

    public function getUnitMarginAttribute(): float
    {
        return (float) $this->unit_price - (float) ($this->purchase_price ?? 0);
    }

    public function getMarginPercentageAttribute(): float
    {
        $purchase = (float) ($this->purchase_price ?? 0);
        if ($purchase <= 0) {
            return 0.0;
        }

        return round((((float) $this->unit_price - $purchase) / $purchase) * 100, 1);
    }
}
