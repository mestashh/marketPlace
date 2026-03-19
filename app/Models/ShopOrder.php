<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property $user_id
 * @property $id
 */
class ShopOrder extends Model
{
    use HasFactory;

    /**
     * @var \Illuminate\Support\HigherOrderCollectionProxy|mixed
     */
    protected $fillable = ['status', 'subtotal_price'];

    public function conversations(): HasMany
    {
        return $this->HasMany(Conversation::class);
    }

    public function orderItems(): HasMany
    {
        return $this->HasMany(OrderItem::class);
    }

    public function payouts(): HasMany
    {
        return $this->HasMany(Payout::class);
    }

    public function order(): BelongsTo
    {
        return $this->BelongsTo(Order::class);
    }

    public function shop(): BelongsTo
    {
        return $this->BelongsTo(Shop::class);
    }
}
