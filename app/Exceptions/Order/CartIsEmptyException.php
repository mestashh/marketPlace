<?php

namespace App\Exceptions\Order;

use Exception;

class CartIsEmptyException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Cart is empty'], 400);
    }
}
