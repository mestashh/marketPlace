<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property mixed|null $product
 * @property mixed $path
 * @property mixed $access_status
 * @method static create(array $array)
 */
class ProductImage extends Model
{
    use HasFactory;
    protected $fillable = ['path', 'product_id', 'access_status', 'is_main', 'position'];

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
