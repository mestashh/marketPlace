<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    protected $fillable = ['pay_method'];

    public function payments(): HasMany
    {
        return $this->HasMany(Payment::class);
    }
}
