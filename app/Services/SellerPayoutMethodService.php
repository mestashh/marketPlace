<?php

namespace App\Services;

use App\Enums\PayoutStatusEnum;
use App\Models\SellerPayoutMethod;
use App\Models\User;

class SellerPayoutMethodService
{
    public function index(User $user)
    {
        if ($user->isAdmin()) {
            $methods = SellerPayoutMethod::query()->paginate(20);

        } else {
            $methods = SellerPayoutMethod::where('seller_id', $user->seller->id)->get();

        }

        return $methods;
    }

    public function create(User $user, array $data)
    {
        return SellerPayoutMethod::create([
            'seller_id' => $user->seller->id,
            'payout_method_id' => $data['payout_method_id'],
            'details' => $data['details'],
            'status' => PayoutStatusEnum::PENDING->value,
        ]);
    }
}
