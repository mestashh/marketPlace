<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = ['rating', 'text'];

    public function user(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->BelongsTo(Product::class);
    }
}
