<?php

namespace App\Policies;

use App\Enums\OrderStatusEnum;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;

class ReviewPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Review $review): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Product $product): bool
    {
        $hasPurchased = $user->orders()
            ->where('status', 'delivered')
            ->whereHas('shopOrders.orderItems.productVariant', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        $alreadyReviewed = Review::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->exists();

        return $hasPurchased && ! $alreadyReviewed;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Review $review): bool
    {
        return $user->hasOrders() && $user->id == $review->user_id;
    }
}
