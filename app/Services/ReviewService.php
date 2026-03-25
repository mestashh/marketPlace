<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class ReviewService
{

    public function index(Product $product)
    {
        return Review::where('status', StatusEnum::ACCESS->value)
            ->where('product_id', $product->id)
            ->get();
    }

    public function create(User $user, array $data, Product $product)
    {
        return Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $data['rating'],
            'text' => $data['text'],
            'status' => StatusEnum::CHECKING->value,
        ]);
    }
}
