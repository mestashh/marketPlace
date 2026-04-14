<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property mixed $cart
 * @property mixed $quantity
 * @property mixed $productVariant
 */
class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'cart_id',
        'product_variant_id',
        'price',
    ];

    public function cart(): BelongsTo
    {
        return $this->BelongsTo(Cart::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->BelongsTo(ProductVariant::class);
    }

    public static function booted(): void
    {
        static::creating(function ($model) {
            $model->uuid = Str::uuid();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
