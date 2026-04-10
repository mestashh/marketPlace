<?php

namespace App\Http\Controllers;

use App\Exceptions\Review\ReviewNotFoundException;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Http\Requests\Review\UpdateReviewRequest;
use App\Http\Resources\Review\ReviewForAdminResource;
use App\Http\Resources\Review\ReviewForUserResource;
use App\Models\Product;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private readonly ReviewService $reviewService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Product $product)
    {
        $review = $this->reviewService->index($request->user(), $product);

        return ReviewForUserResource::collection($review);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReviewRequest $request, Product $product)
    {
        $this->authorize('create', [Review::class, $product]);
        $review = $this->reviewService->create($request->user(), $request->validated(), $product);

        return new ReviewForUserResource($review);
    }

    /**
     * Display the specified resource.
     *
     * @throws ReviewNotFoundException
     */
    public function show(Request $request, Product $product, Review $review)
    {
        $review = $this->reviewService->show($request->user(), $review);

        if ($request->user() && $request->user()->isAdmin()) {
            return new ReviewForAdminResource($review);
        } else {
            return new ReviewForUserResource($review);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Product $product, Review $review)
    {
        $this->authorize('update', [Review::class, $review]);
        $review->update($request->validated());

        return new ReviewForUserResource($review);
    }
}
