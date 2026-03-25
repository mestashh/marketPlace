<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\ChangeStatusRequest;
use App\Http\Requests\Seller\StoreSellerRequest;
use App\Http\Requests\Seller\UpdateSellerRequest;
use App\Http\Resources\SellerResource;
use App\Models\Seller;
use App\Services\SellerService;

class SellerController extends Controller
{
    public function __construct(
        private readonly SellerService $sellerService,
    ) {
        $this->authorizeResource(Seller::class, 'seller');
    }

    public function index()
    {
        $sellers = Seller::query()->paginate(20);

        return SellerResource::collection($sellers);
    }

    public function show(Seller $seller)
    {
        return new SellerResource($seller);
    }

    public function store(StoreSellerRequest $request)
    {
        $seller = $this->sellerService->create($request->user(), $request->validated());

        return new SellerResource($seller);
    }

    public function update(UpdateSellerRequest $request, Seller $seller)
    {
        $seller->update($request->validated());

        return new SellerResource($seller);
    }

    public function destroy(Seller $seller)
    {
        $seller->delete();

        return response()->noContent();
    }

    public function changeStatus(ChangeStatusRequest $request, Seller $seller)
    {
        $this->authorize('changeStatus', $seller);
        $seller->update($request->validated());

        return new SellerResource($seller);
    }
}
