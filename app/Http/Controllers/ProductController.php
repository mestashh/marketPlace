<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\Product\ProductForAdminResource;
use App\Http\Resources\Product\ProductForUserResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $productService,
    ) {
        $this->authorizeResource(Product::class, 'product');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user() && $request->user()->isAdmin()) {
            $products = Product::paginate(20);

            return ProductForAdminResource::collection($products);
        }
        $products = Product::where('access_status', StatusEnum::ACCESS->value)->paginate(20);

        return ProductForUserResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request->user(), $request->validated());

        return new ProductForUserResource($product);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return new ProductForUserResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return new ProductForUserResource($product);
    }
}
