<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payout extends Model
{
    protected $fillable = [
        'status',
        'amount',
    ];

    protected $hidden = [
        'status',
        'amount',
    ];

    public function shopOrder(): BelongsTo
    {
        return $this->BelongsTo(ShopOrder::class);
    }

    public function sellerPayoutMethod(): BelongsTo
    {
        return $this->BelongsTo(SellerPayoutMethod::class);
    }
}
