<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'medication_id',
        'reference_no',
        'movement_date',
        'supplier',
        'packaging_unit',
        'type',
        'quantity',
        'purchase_price',
        'selling_price',
        'user_id',
        'performed_by_name',
        'notes',
    ];

    protected $casts = [
        'movement_date' => 'date',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function getEffectivePurchasePriceAttribute(): float
    {
        return (float) ($this->purchase_price ?? $this->medication?->purchase_price ?? 0);
    }

    public function getEffectiveSellingPriceAttribute(): float
    {
        return (float) ($this->selling_price ?? $this->medication?->unit_price ?? 0);
    }

    public function getUnitMarginAttribute(): float
    {
        return $this->effective_selling_price - $this->effective_purchase_price;
    }

    public function getTotalPurchaseAttribute(): float
    {
        return (float) ($this->quantity * $this->effective_purchase_price);
    }

    public function getTotalSellingAttribute(): float
    {
        return (float) ($this->quantity * $this->effective_selling_price);
    }

    public function getTotalMarginAttribute(): float
    {
        return $this->total_selling - $this->total_purchase;
    }

    public function getMarginPercentageAttribute(): float
    {
        $cost = $this->effective_purchase_price;
        if ($cost <= 0) {
            return 0.0;
        }

        return round((($this->effective_selling_price - $cost) / $cost) * 100, 1);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
