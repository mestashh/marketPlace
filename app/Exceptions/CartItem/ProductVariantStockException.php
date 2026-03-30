<?php

namespace App\Exceptions\CartItem;

use Exception;

class ProductVariantStockException extends Exception
{
    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'Seller does not have enough product'], 400);
    }
}
