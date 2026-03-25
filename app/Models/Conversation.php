<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * @property mixed $user_id
 * @property mixed $seller_id
 * @property mixed $status
 * @property mixed $admin_id
 * @property mixed $id
 * @method static where(string $string, $id)
 * @method static create(array $array)
 */
class Conversation extends Model
{
    use HasFactory;
    protected $fillable = ['status', 'shop_order_id', 'user_id', 'seller_id'];

    public function shopOrder(): BelongsTo
    {
        return $this->BelongsTo(ShopOrder::class);
    }

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function seller(): BelongsTo
    {
        return $this->BelongsTo(Seller::class);
    }

    public function admin(): BelongsTo
    {
        return $this->BelongsTo(Admin::class);
    }

    public function messages(): HasMany
    {
        return $this->HasMany(Message::class);
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
