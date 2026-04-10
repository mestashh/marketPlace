<?php

namespace App\Exceptions\Order;

use Exception;

class AddressNotFoundException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Address not found, try another one'], 422);
    }
}
