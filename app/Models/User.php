<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property mixed $id
 */
class User extends Authenticatable
{
    use HasApiTokens, hasFactory;

    protected $fillable = ['name', 'email', 'email_verified_at', 'phone', 'password', 'access_status'];

    protected $hidden = ['remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function addresses(): HasMany
    {
        return $this->HasMany(Address::class);
    }

    public function admin(): HasOne
    {
        return $this->HasOne(Admin::class);
    }

    public function isAdmin(): bool
    {
        return $this->admin()->exists();
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    public function conversations(): HasMany
    {
        return $this->HasMany(Conversation::class);
    }

    public function messages(): HasMany
    {
        return $this->HasMany(Message::class);
    }

    public function orders(): HasMany
    {
        return $this->HasMany(Order::class);
    }

    public function reviews(): HasMany
    {
        return $this->HasMany(Review::class);
    }
}
