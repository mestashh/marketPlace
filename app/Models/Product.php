<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use hasFactory;
    protected $fillable = ['name', 'description', 'status'];

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
}
