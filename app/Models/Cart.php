<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property mixed $user_id
 * @property mixed $cartItems
 * @method static inRandomOrder()
 * @method static find(int $int)
 */
class Cart extends Model
{
    protected $fillable = ['user_id', 'product_variant_id', 'quantity', 'price'];

    public function cartItems(): HasMany
    {
        return $this->HasMany(CartItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
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
