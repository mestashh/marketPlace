<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

/**
 * @property mixed $user_id
 * @property mixed $user
 * @property mixed $id
 * @property mixed $access_status
 *
 * @method static inRandomOrder()
 * @method static create(array $array)
 * @method static find(mixed $id)
 * @method static findOrFail(mixed $id)
 * @method static where(string $string, string $value)
 * @method static paginate(int $int)
 */
class Seller extends Authenticatable
{
    use hasFactory;

    protected $fillable = ['user_id', 'balance', 'withdrawable_balance', 'TIN', 'display_name', 'access_status'];

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

    public function shop(): HasOne
    {
        return $this->HasOne(Shop::class);
    }

    public function hasShop(): bool
    {
        return $this->shop()->exists();
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
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
