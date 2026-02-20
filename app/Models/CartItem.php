<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'quantity',
        'product_variant_id',
    ];

    public function cart(): BelongsTo
    {
        return $this->BelongsTo(Cart::class);
    }

    public function product_variant(): BelongsTo
    {
        return $this->BelongsTo(ProductVariant::class);
    }
}
