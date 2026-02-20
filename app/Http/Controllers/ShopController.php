<?php

namespace App\Http\Controllers;

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
            'description' => $data['description'],
        ]);

        return new ShopResource($shop)
            ->response(201);
    }

    public function update(UpdateShopRequest $request, Shop $shop)
    {
        return new ShopResource($shop->update($request->validated()))
            ->response(202);
    }

    public function delete(Shop $shop)
    {
        $shop->delete();

        return response()->noContent();
    }

}
