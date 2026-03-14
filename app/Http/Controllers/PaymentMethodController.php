<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentMethod\StorePaymentMethodRequest;
use App\Http\Requests\PaymentMethod\UpdatePaymentMethodRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(PaymentMethod::class, 'paymentMethod');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment_method = PaymentMethod::query()->paginate(20);

        return PaymentMethodResource::collection($payment_method);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentMethodRequest $request)
    {
        $data = $request->validated();
        $payment_method = PaymentMethod::create([
            'payment_method' => $data['payment_method'],
        ]);

        return new PaymentMethodResource($payment_method);
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return new PaymentMethodResource($paymentMethod);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        $paymentMethod->update($request->validated());

        return new PaymentMethodResource($paymentMethod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return response()->noContent();
    }
}
