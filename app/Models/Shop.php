<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shop extends Model
{
    use hasFactory;
    protected $fillable = ['name', 'description', 'status'];

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
}
