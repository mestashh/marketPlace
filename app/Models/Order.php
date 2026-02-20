<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = ['status', 'total_price'];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->BelongsTo(Address::class);
    }

    public function payment(): HasMany
    {
        return $this->HasMany(Payment::class);
    }

    public function shopOrders(): HasMany
    {
        return $this->HasMany(ShopOrder::class);
    }
}
