<?php

namespace App\Exceptions\Shop;

use Exception;

class ShopNotFoundException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Shop not found'], 422);
    }
}
