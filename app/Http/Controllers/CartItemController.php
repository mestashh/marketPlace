<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\StoreCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Http\Resources\CartItemResource;
use App\Models\CartItem;
use App\Models\ProductVariant;

class CartItemController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(CartItem::class, 'item');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCartItemRequest $request)
    {
        $cart = $request->user()->cart;
        $data = $request->validated();

        $product = ProductVariant::findOrFail($data['product_variant_id']);

        $item = $cart->cartItems()->create([
            'product_variant_id' => $product->id,
            'quantity' => $data['quantity'],
            'price' => $product->price,
        ]);

        return new CartItemResource($item);
    }

    /**
     * Display the specified resource.
     */
    public function show(CartItem $item)
    {
        return new CartItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartItemRequest $request, CartItem $item)
    {
        $item->update($request->validated());

        return new CartItemResource($item);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CartItem $item)
    {
        $item->delete();

        return response()->noContent();
    }
}
