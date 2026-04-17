<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'tracking',
        'reorder_level',
        'notes',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(ProductUnit::class);
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function onHand(): int
    {
        if ($this->tracking === 'serial') {
            return (int) $this->units()->where('status', 'in_stock')->count();
        }

        $net = (int) $this->stockMovements()
            ->selectRaw("COALESCE(SUM(CASE WHEN direction = 'in' THEN qty WHEN direction = 'out' THEN -qty ELSE 0 END), 0) AS net")
            ->value('net');

        return $net;
    }
}
