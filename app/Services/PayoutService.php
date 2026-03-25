<?php

namespace App\Services;

use App\Models\Payout;
use App\Models\User;

class PayoutService
{
    public function index(User $user)
    {
        if ($user->isAdmin()) {
            return Payout::query()->paginate(20);
        } elseif ($user->isSeller()) {
            return Payout::where('seller_id', $user->seller->id)->get();
        }

        return response('No access');
    }

    public function store(User $user, array $data)
    {
        return Payout::create([
            'seller_id' => $user,
            'amount' => $data['amount'],
        ]);
    }
}
