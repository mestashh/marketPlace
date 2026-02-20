<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = ['status', 'amount'];

    public function order(): BelongsTo
    {
        return $this->BelongsTo(Order::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->BelongsTo(PaymentMethod::class);
    }
}
