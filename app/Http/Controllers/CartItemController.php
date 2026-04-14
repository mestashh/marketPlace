<?php

namespace App\Http\Controllers;

use App\Exceptions\CartItem\ProductVariantStockException;
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
        $this->authorize('create', [CartItem::class, ProductVariant::findOrFail($data['product_variant_id'])]);
        $item = $this->cartItemService->store($request->user()->cart, $data);

        return new CartItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @throws ProductVariantStockException
     */
    public function update(UpdateCartItemRequest $request, CartItem $item)
    {
        $this->authorize('update', $item);
        $cartItem = $this->cartItemService->update($request->validated(), $item);

        return new CartItemResource($cartItem);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $item)
    {
        $this->authorize('delete', $item);
        $item->delete();

        return response()->noContent();
    }
}
