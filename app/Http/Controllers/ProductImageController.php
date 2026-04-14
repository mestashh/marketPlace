<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\ProductImage\StoreProductImageRequest;
use App\Http\Requests\ProductImage\UpdateProductImageRequest;
use App\Http\Resources\ProductImage\ProductImageForAdminResource;
use App\Http\Resources\ProductImage\ProductImageForUserResource;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Services\ProductImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function __construct(private readonly ProductImageService $productImageService) {}

    private function isOwnerOrAdmin(?User $user, Product $product): bool
    {
        return $user &&
            ($user->isAdmin() ||
                $user->isSeller() &&
                $user->seller->hasShop() &&
                $user->seller->id === $product->shop->seller->id);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Product $product)
    {
        $this->authorize('viewAny', ProductImage::class);
        if ($this->isOwnerOrAdmin($request->user(), $product)) {
            $images = $product->productImages()->get();

            return ProductImageForAdminResource::collection($images);
        }
        $images = $product->productImages()->where('access_status', StatusEnum::ACCESS->value)->get();

        return ProductImageForUserResource::collection($images);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductImageRequest $request, Product $product)
    {
        $this->authorize('create', [ProductImage::class, $product]);
        $image = $this->productImageService->create($request->validated(), $product);

        return new ProductImageForUserResource($image);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product, ProductImage $productImage)
    {
        $this->authorize('view', $productImage);
        return $this->isOwnerOrAdmin($request->user(), $productImage->product) ?
            new ProductImageForAdminResource($productImage) :
            new ProductImageForUserResource($productImage);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductImageRequest $request, Product $product, ProductImage $productImage)
    {
        $this->authorize('update', $productImage);
        $productImage->update($request->validated());

        return new ProductImageForUserResource($productImage);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductImage $productImage)
    {
        $this->authorize('delete', $productImage);
        Storage::disk('public')->delete($productImage->path);
        $productImage->delete();

        return response()->noContent();
    }
}
