<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Product;
use App\Models\Review;
use App\Services\ReviewService;

class ReviewController extends Controller
{
    public function __construct(private readonly ReviewService $reviewService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        $review = $this->reviewService->index($product);

        return ReviewResource::collection($review);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request, Product $product)
    {
        $this->authorize('create', [Review::class, $product]);
        $review = $this->reviewService->create($request->user(), $request->validated(), $product);

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
