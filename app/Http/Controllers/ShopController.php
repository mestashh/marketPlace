<?php

namespace App\Http\Controllers;

use App\Enums\StatusEnum;
use App\Exceptions\Shop\ShopNotFoundException;
use App\Http\Requests\Shop\StoreShopRequest;
use App\Http\Requests\Shop\UpdateShopRequest;
use App\Http\Resources\Shop\ShopForAdminResource;
use App\Http\Resources\Shop\ShopForUserResource;
use App\Models\Shop;
use App\Services\ShopService;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __construct(
        private readonly ShopService $shopService,
    ) {
        $this->authorizeResource(Shop::class, 'shop');
    }

    public function index(Request $request)
    {
        if ($request->user() && $request->user()->isAdmin()) {
            $shops = Shop::paginate(20);

            return ShopForAdminResource::collection($shops);
        } else {
            $shops = Shop::where('access_status', StatusEnum::ACCESS->value)
                ->paginate(20);

            return ShopForUserResource::collection($shops);
        }

    }

    /**
     * @throws ShopNotFoundException
     */
    public function show(Request $request, Shop $shop)
    {
        if ($shop->access_status == StatusEnum::ACCESS->value) {
            if ($request->user() && ($request->user()->id === $shop->seller->user->id || $request->user()->isAdmin())) {
                return new ShopForAdminResource($shop);
            } else {
                return new ShopForUserResource($shop);
            }
        } else {
            throw new ShopNotFoundException;
        }

    }

    public function store(StoreShopRequest $request)
    {
        $shop = $this->shopService->create($request->user(), $request->validated());

        return new ShopForUserResource($shop);
    }

    public function update(UpdateShopRequest $request, Shop $shop)
    {
        $shop->update($request->validated());

        return new ShopForUserResource($shop);
    }
}
