<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property mixed $product
 * @property mixed $access_status
 * @method static inRandomOrder()
 * @method static findOrFail(mixed $product_variant_id)
 * @method static where(string $string, mixed $product_variant_id)
 */
class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'stock', 'sku', 'access_status', 'product_id', 'uuid', 'productVariant_id'];

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
