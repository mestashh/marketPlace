<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = ['status', 'shop_order_id', 'user_id', 'seller_id'];

    public function shop_order(): BelongsTo
    {
        return $this->BelongsTo(ShopOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function seller(): BelongsTo
    {
        return $this->BelongsTo(Seller::class);
    }

    public function admin(): BelongsTo
    {
        return $this->BelongsTo(Admin::class);
    }

    public function messages(): HasMany
    {
        return $this->HasMany(Message::class);
    }
}
