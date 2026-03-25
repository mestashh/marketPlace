<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ChangeStatusRequest;
use App\Http\Requests\Shop\StoreShopRequest;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Http\Resources\ShopResource;
use App\Models\Shop;
use App\Services\ShopService;

class ShopController extends Controller
{
    public function __construct(
        private readonly ShopService $shopService,
    ) {
        $this->authorizeResource(Shop::class, 'shop');
    }

    public function index()
    {
        $shops = Shop::query()->paginate(20);

        return ShopResource::collection($shops);
    }

    public function show(Shop $shop)
    {
        return new ShopResource($shop);
    }

    public function store(StoreShopRequest $request)
    {
        $shop = $this->shopService->create($request->user(), $request->validated());

        return new ShopResource($shop);
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
