<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property mixed $order
 */
class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['payment_method_id', 'order_id', 'status', 'amount', 'user_id'];

    public function order(): BelongsTo
    {
        return $this->BelongsTo(Order::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->BelongsTo(PaymentMethod::class);
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
