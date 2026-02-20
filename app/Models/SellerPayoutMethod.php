<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SellerPayoutMethod extends Model
{
    protected $fillable = [
        'status',
    ];
    protected $casts = [
        'details' => 'array',
    ];

    public function seller(): BelongsTo
    {
        return $this->BelongsTo(Seller::class);
    }

    public function payoutMethod(): BelongsTo
    {
        return $this->BelongsTo(PayoutMethod::class);
    }

    public function payouts(): HasMany
    {
        return $this->HasMany(Payout::class);
    }
}
