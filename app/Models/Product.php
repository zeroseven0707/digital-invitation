<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image_path',
        'price',
        'stock',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price'     => 'integer',
        'stock'     => 'integer',
        'is_active' => 'boolean',
    ];

    public function giftOrders()
    {
        return $this->hasMany(GiftOrder::class);
    }

    /**
     * Sisa stok yang belum terbeli (paid orders).
     */
    public function remainingStock(): int
    {
        $sold = $this->giftOrders()->where('status', 'paid')->count();
        return max(0, $this->stock - $sold);
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->remainingStock() > 0;
    }
}
