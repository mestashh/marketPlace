<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\ProductVariant\storeProductVariantRequest;
use App\Http\Requests\ProductVariant\UpdateProductVariantRequest;
use App\Http\Resources\ProductVariant\ProductVariantForAdminResource;
use App\Http\Resources\ProductVariant\ProductVariantForUserResource;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
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
        $this->authorize('viewAny', ProductVariant::class);
        if ($this->isOwnerOrAdmin($request->user(), $product)) {
            $variants = $product->productVariants()->get();

            return ProductVariantForAdminResource::collection($variants);
        }
        $variants = $product->productVariants()->where('access_status', StatusEnum::ACCESS->value)->get();

        return ProductVariantForUserResource::collection($variants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductVariantRequest $request, Product $product)
    {
        $this->authorize('create', [ProductVariant::class, $product]);
        $variant = $product->productVariants()->create($request->validated());

        return new ProductVariantForUserResource($variant);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Product $product, ProductVariant $productVariant)
    {
        $this->authorize('view', $productVariant);
        return $this->isOwnerOrAdmin($request->user(), $productVariant->product) ?
            new ProductVariantForAdminResource($productVariant) :
            new ProductVariantForUserResource($productVariant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductVariantRequest $request, Product $product, ProductVariant $productVariant)
    {
        $this->authorize('update', $productVariant);
        $productVariant->update($request->validated());

        return new ProductVariantForUserResource($productVariant);

    }
}
