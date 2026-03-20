<?php

namespace App\Http\Controllers;

use App\Enums\PayoutStatusEnum;
use App\Http\Requests\SellerPayoutMethod\StoreSellerPayoutMethodRequest;
use App\Http\Requests\SellerPayoutMethod\UpdateSellerPayoutMethodRequest;
use App\Http\Resources\SellerPayoutMethodResource;
use App\Models\SellerPayoutMethod;
use Illuminate\Http\Request;

class SellerPayoutMethodController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(SellerPayoutMethod::class, 'sellerPayoutMethod');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->isAdmin()) {
            $methods = SellerPayoutMethod::query()->paginate(20);

        } else {
            $methods = SellerPayoutMethod::where('seller_id', $user->seller->id)->get();

        }
        return SellerPayoutMethodResource::collection($methods);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSellerPayoutMethodRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $sellerPayoutMethod = SellerPayoutMethod::create([
            'seller_id' => $user->seller->id,
            'payout_method_id' => $data['payout_method_id'],
            'details' => $data['details'],
            'status' => PayoutStatusEnum::PENDING->value,
        ]);
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
        return response()
            ->noContent();
    }
}
