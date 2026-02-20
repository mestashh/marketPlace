<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductVariant extends Model
{
    protected $fillable = ['name', 'description', 'price', 'stock', 'sku'];

    public function cartItems(): HasMany
    {
        return $this->HasMany(CartItem::class);
    }

    public function orderItem(): HasMany
    {
        return $this->HasMany(OrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class);
    }
}
