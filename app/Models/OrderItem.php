<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static create(array $array)
 */
class OrderItem extends Model
{
    use HasFactory;
    protected $fillable = ['quantity', 'price_at_purchase', 'shop_order_id', 'product_variant_id'];

    public function shopOrder(): BelongsTo
    {
        return $this->BelongsTo(ShopOrder::class);
    }

    public function productVariant(): BelongsTo
    {
        return $this->BelongsTo(ProductVariant::class);
    }
}
