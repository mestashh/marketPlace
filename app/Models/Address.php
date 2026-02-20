<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * @property mixed $user_id
 */
class Address extends Model
{
    protected $fillable = [
        'user_id',
        'country',
        'city',
        'street',
        'house',
        'phone',
        'description',
    ];


    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->HasMany(Order::class);
    }
}
