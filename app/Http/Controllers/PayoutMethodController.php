<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payout\StorePayoutMethodRequest;
use App\Http\Requests\Payout\UpdatePayoutMethodRequest;
use App\Http\Resources\PayoutMethodResource;
use App\Models\PayoutMethod;

class PayoutMethodController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PayoutMethod::class, 'payoutMethod');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payoutMethod = PayoutMethod::query()->paginate(20);

        return PayoutMethodResource::collection($payoutMethod);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePayoutMethodRequest $request)
    {
        $data = $request->validated();
        $payoutMethod = PayoutMethod::create([
            'payout_method' => $data['payout_method'],
        ]);

        return new PayoutMethodResource($payoutMethod);
    }

    /**
     * Display the specified resource.
     */
    public function show(PayoutMethod $payoutMethod)
    {
        return new PayoutMethodResource($payoutMethod);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayoutMethodRequest $request, PayoutMethod $payoutMethod)
    {
        $payoutMethod = $payoutMethod->update($request->validated());

        return new PayoutMethodResource($payoutMethod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PayoutMethod $payoutMethod)
    {
        $payoutMethod->delete();
        return response()->noContent();
    }
}
