<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $review = Review::where('status', StatusEnum::ACCESS->value)
            ->where('product_id', $product->id)
            ->get();

        return ReviewResource::collection($review);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request, Product $product)
    {
        $this->authorize('create', [Review::class, $product]);
        $user = $request->user();
        $data = $request->validated();
        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $data['rating'],
            'text' => $data['text'],
            'status' => StatusEnum::CHECKING->value,
        ]);

        return new ReviewResource($review);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, Review $review)
    {
        return new ReviewResource($review);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Product $product, Review $review)
    {
        $this->authorize('update', [Review::class, $review]);
        $review->update($request->validated());

        return new ReviewResource($review);
    }
}
