<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayoutMethod extends Model
{
    protected $fillable = [
        'pay_method',
    ];
    protected $hidden = [
        'pay_method',
    ];

    public function payoutMethods(): HasMany
    {
        return $this->HasMany(SellerPayoutMethod::class);
    }
}
