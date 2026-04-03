<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static inRandomOrder()
 * @method static create(array $array)
 * @method static paginate(int $int)
 */
class PaymentMethod extends Model
{
    use HasFactory;
    protected $fillable = ['payment_method'];

    public function payments(): HasMany
    {
        return $this->HasMany(Payment::class);
    }
}
