<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatusEnum;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $paymentService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize(Payment::class, 'payment');
        $payment = Payment::where('user_id', $request->user()->id)->paginate(20);

        return PaymentResource::collection($payment);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request, Order $order)
    {
        $this->authorize('create', [Payment::class, $order]);
        $payment = $this->paymentService->store($request->user(), $request->validated(), $order);

        return new PaymentResource($payment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order, Payment $payment)
    {
        $this->authorize('view', [Payment::class, $payment]);

        return new PaymentResource($payment);
    }
}
