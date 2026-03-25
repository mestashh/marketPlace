<?php

namespace App\Services;

use App\Models\Seller;
use App\Models\User;

class SellerService
{
    public function create(User $user, array $data)
    {
        return Seller::create([
            'user_id' => $user->id,
            'TIN' => $data['TIN'],
        ]);
    }
}
