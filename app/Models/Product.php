<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property mixed $shop_id
 * @property mixed $productVariants
 * @property mixed $id
 * @property mixed $uuid
 * @property mixed $shop
 * @property mixed|string $access_status
 * @method static inRandomOrder()
 * @method static create(array $array)
 * @method static find(mixed $id)
 * @method static findOrFail(mixed $id)
 * @method static where(string $string, mixed $position)
 * @method static paginate(int $int)
 */
class Product extends Model
{
    use hasFactory;

    protected $fillable = ['name', 'description', 'status', 'category_id', 'shop_id', 'access_status', 'quantity'];

    public function productImages(): HasMany
    {
        return $this->HasMany(ProductImage::class);
    }

    public function shop(): BelongsTo
    {
        return $this->BelongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->BelongsTo(Category::class);
    }

    public function productVariants(): HasMany
    {
        return $this->HasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->HasMany(Review::class);
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
