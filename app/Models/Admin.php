<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property mixed $role
 */
class Admin extends Authenticatable
{

    protected $fillable = ['role'];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function conversations(): HasMany
    {
        return $this->HasMany(Conversation::class);
    }

}
