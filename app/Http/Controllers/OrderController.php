<?php

namespace App\Http\Controllers;

use App\Exceptions\Order\AddressNotFoundException;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class OrderController extends Controller
{
    public function __construct(private readonly OrderService $orderService)
    {
        $this->authorizeResource(Order::class, 'order');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $order = $this->orderService->index($request->user());

        return OrderResource::collection($order);
    }

    /**
     * Store a newly created resource in storage.
     * @throws Throwable
     */
    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->store($request->user(), $request->validated(), $request->user()->cart);

        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    /**
     * Update the specified resource in storage.
     * @throws AddressNotFoundException
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order = $this->orderService->update($request->user(), $request->validated(), $order);

        return new OrderResource($order);
    }
}
