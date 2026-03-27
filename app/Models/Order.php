<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property mixed $status
 * @property mixed $user_id
 * @property mixed $id
 * @property mixed $total_price
 * @property mixed $user
 * @method static inRandomOrder()
 * @method static where(string $string, mixed $id)
 * @method static create(array $array)
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'address_id', 'status', 'total_price'];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->BelongsTo(Address::class);
    }

    public function payments(): HasMany
    {
        return $this->HasMany(Payment::class);
    }

    public function shopOrders(): HasMany
    {
        return $this->HasMany(ShopOrder::class);
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
