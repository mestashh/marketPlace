<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ChangeStatusRequest;
use App\Http\Requests\Shop\StoreShopRequest;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;

class ShopController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Shop::class, 'shop');
    }

    public function index()
    {
        $shop = Shop::query()->paginate(20);

        return ShopResource::collection($shop);
    }

    public function show(Shop $shop)
    {
        return new ShopResource($shop);
    }

    public function store(StoreShopRequest $request)
    {
        $data = $request->validated();

        $shop = Shop::create([
            'name' => $data['name'],
            'seller_id' => $request->user()->seller->id,
            'description' => $data['description'],
        ]);

        return new ShopResource($shop)
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $shop->update($request->validated());

        return new ShopResource($shop);
    }

    public function destroy(Shop $shop)
    {
        $shop->delete();

        return response()->noContent();
    }

    public function changeStatus(ChangeStatusRequest $request, Shop $shop)
    {
        $this->authorize('changeStatus', $shop);
        $shop->update($request->validated());

        return new ShopResource($shop);
    }
}
