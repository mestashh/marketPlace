<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductImage\StoreProductImageRequest;
use App\Http\Requests\ProductImage\UpdateProductImageRequest;
use App\Http\Resources\ProductImageResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductImageService;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function __construct(private readonly ProductImageService $productImageService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        return ProductImageResource::collection($product->productImages()->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductImageRequest $request, Product $product)
    {
        $this->authorize('create', [ProductImage::class, $product]);
        $image = $this->productImageService->create($request->validated(), $product);

        return new ProductImageResource($image);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductImage $productImage)
    {
        return new ProductImageResource($productImage);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductImageRequest $request, Product $product, ProductImage $productImage)
    {
        $this->authorize('update', [ProductImage::class, $productImage]);
        $productImage->update($request->validated());

        return new ProductImageResource($productImage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductImage $productImage)
    {
        $this->authorize('delete', [ProductImage::class, $productImage]);
        Storage::disk('public')->delete($productImage->path);
        $productImage->delete();

        return response()->noContent();
    }
}
