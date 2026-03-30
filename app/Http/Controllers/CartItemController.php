<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\ProductVariant;
use App\Services\CartItemService;

class CartItemController extends Controller
{
    public function __construct(private readonly CartItemService $cartItemService)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartItemRequest $request)
    {
        $data = $request->validated();
        $this->authorize('create', [CartItem::class, ProductVariant::where('id', $data['product_variant_id'])->first()]);
        $item = $this->cartItemService->store($request->user()->cart, $data);

        return new CartItemResource($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $item)
    {
        $this->authorize('view', CartItem::class);
        return new CartItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, CartItem $item)
    {
        $this->authorize('update', CartItem::class);
        $item->update($request->validated());

        return new CartItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $item)
    {
        $this->authorize('delete', CartItem::class);
        $item->delete();

        return response()->noContent();
    }
}
