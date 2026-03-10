<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

/**
 * @property mixed $role
 */
class Admin extends Authenticatable
{
    use hasFactory;

    protected $fillable = ['role', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function conversations(): HasMany
    {
        return $this->HasMany(Conversation::class);
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
