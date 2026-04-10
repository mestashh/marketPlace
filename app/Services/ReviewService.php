<?php

namespace App\Services;

use App\Enums\StatusEnum;
use App\Exceptions\Review\ReviewNotFoundException;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class ReviewService
{
    public function index(?User $user, Product $product)
    {
        if ($user?->isAdmin()) {
            return Review::query()->paginate(20);
        } else {
            return Review::where('access_status', StatusEnum::ACCESS->value)
                ->where('product_id', $product->id)
                ->paginate(20);
        }
    }

    public function create(User $user, array $data, Product $product)
    {
        return Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $data['rating'],
            'text' => $data['text'],
            'access_status' => StatusEnum::CHECKING->value,
        ]);
    }

    /**
     * @throws ReviewNotFoundException
     */
    public function show(?User $user, Review $review): Review
    {
        if (! $user?->isAdmin() && $review->access_status !== StatusEnum::ACCESS->value) {
            throw new ReviewNotFoundException;
        }
        return $review;
    }
}
