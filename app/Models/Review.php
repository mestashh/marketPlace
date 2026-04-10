<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property mixed $user_id
 * @property mixed $access_status
 * @method static create(array $array)
 * @method static where(string $string, string $value)
 */
class Review extends Model
{
    use HasFactory;

    protected $fillable = ['rating', 'text', 'user_id', 'product_id', 'access_status'];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class);
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
