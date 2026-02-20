<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = ['quantity', 'price_at_purchase'];

    public function shopOrder(): BelongsTo
    {
        return $this->BelongsTo(ShopOrder::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->BelongsTo(ProductVariant::class);
    }
}
