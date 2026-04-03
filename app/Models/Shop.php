<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @method static inRandomOrder()
 * @method static take(int $int)
 * @method static find(int $int)
 * @method static create(array $array)
 * @method static findOrFail(mixed $id)
 * @method static paginate(int $int)
 * @property mixed $seller
 * @property mixed $seller_id
 */
class Shop extends Model
{
    use hasFactory;

    protected $fillable = ['name', 'description', 'status', 'seller_id', 'access_status'];

    public function products(): HasMany
    {
        return $this->HasMany(Product::class);
    }

    public function shopOrders(): HasMany
    {
        return $this->HasMany(ShopOrder::class);
    }

    public function seller(): BelongsTo
    {
        return $this->BelongsTo(Seller::class);
    }

    public function hasProduct(): bool
    {
        return $this->products()->exists();
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
