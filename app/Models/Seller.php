<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property mixed $user_id
 */
class Seller extends Authenticatable
{
    protected $fillable = ['user_id', 'balance', 'withdrawable_balance', 'TIN', 'display_name'];


    public function conversations(): HasMany
    {
        return $this->HasMany(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function payoutMethods(): HasMany
    {
        return $this->HasMany(SellerPayoutMethod::class);
    }

    public function shops(): HasMany
    {
        return $this->HasMany(Shop::class);
    }
}
