<?php

namespace App\Services;

use App\Exceptions\Payout\PayoutAccessException;
use App\Models\Payout;
use App\Models\User;

class PayoutService
{
    /**
     * @throws PayoutAccessException
     */
    public function index(User $user)
    {
        if ($user->isAdmin()) {
            return Payout::paginate(20);
        } elseif ($user->isSeller()) {
            return Payout::where('seller_id', $user->seller->id)->paginate(20);
        } else {
            throw new PayoutAccessException;
        }
    }

    public function store(User $user, array $data)
    {
        return Payout::create([
            'seller_id' => $user,
            'amount' => $data['amount'],
        ]);
    }
}
