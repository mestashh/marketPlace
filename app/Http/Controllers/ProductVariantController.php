<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductVariant\storeProductVariantRequest;
use App\Http\Requests\ProductVariant\UpdateProductVariantRequest;
use App\Http\Resources\ProductVariantResource;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Product $product)
    {
        return ProductVariantResource::collection($product->productVariants);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductVariantRequest $request, Product $product)
    {
        $this->authorize('create', [ProductVariant::class, $product]);
        $variant = $product->productVariants()->create($request->validated());

        return new ProductVariantResource($variant);
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductVariant $productVariant)
    {
        return new ProductVariantResource($productVariant);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductVariantRequest $request, Product $product, ProductVariant $productVariant)
    {
        $this->authorize('update', $productVariant);
        $productVariant->update($request->validated());

        return new ProductVariantResource($productVariant);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product, ProductVariant $productVariant)
    {
        $this->authorize('delete', $productVariant);
        $productVariant->delete();

        return response()->noContent();
    }
}
