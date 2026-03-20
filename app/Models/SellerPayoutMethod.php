<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property mixed $seller_id
 */
class SellerPayoutMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'payout_method_id',
        'seller_id',
        'details',
    ];

    protected $casts = [
        'details' => 'json',
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
