<?php

namespace App\Http\Controllers;

use App\Enums\PayoutStatusEnum;
use App\Http\Requests\SellerPayoutMethod\StoreSellerPayoutMethodRequest;
use App\Http\Requests\SellerPayoutMethod\UpdateSellerPayoutMethodRequest;
use App\Http\Resources\SellerPayoutMethodResource;
use App\Models\SellerPayoutMethod;
use App\Services\SellerPayoutMethodService;
use Illuminate\Http\Request;

class SellerPayoutMethodController extends Controller
{
    public function __construct(
        private readonly SellerPayoutMethodService $sellerPayoutMethodService,
    ) {
        $this->authorizeResource(SellerPayoutMethod::class, 'sellerPayoutMethod');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $methods = $this->sellerPayoutMethodService->index($request->user());

        return SellerPayoutMethodResource::collection($methods);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSellerPayoutMethodRequest $request)
    {
        $sellerPayoutMethod = $this->sellerPayoutMethodService->create($request->user(), $request->validated());

        return new SellerPayoutMethodResource($sellerPayoutMethod);
    }

    /**
     * Display the specified resource.
     */
    public function show(SellerPayoutMethod $sellerPayoutMethod)
    {
        return new SellerPayoutMethodResource($sellerPayoutMethod);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSellerPayoutMethodRequest $request, SellerPayoutMethod $sellerPayoutMethod)
    {
        $sellerPayoutMethod->update($request->validated());

        return new SellerPayoutMethodResource($sellerPayoutMethod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SellerPayoutMethod $sellerPayoutMethod)
    {
        $sellerPayoutMethod->delete();

        return response()->noContent();
    }
}
