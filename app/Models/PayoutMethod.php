<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static inRandomOrder()
 */
class PayoutMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'payout_method',
    ];

    public function payoutMethods(): HasMany
    {
        return $this->HasMany(SellerPayoutMethod::class);
    }
}
