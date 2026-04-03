<?php

namespace App\Http\Controllers;

use App\Exceptions\Payout\PayoutAccessException;
use App\Http\Requests\Payout\StorePayoutRequest;
use App\Http\Resources\PayoutResource;
use App\Models\Payout;
use App\Services\PayoutService;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    public function __construct(private readonly PayoutService $payoutService) {}

    /**
     * Display a listing of the resource.
     * @throws PayoutAccessException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Payout::class);
        $payout = $this->payoutService->index($request->user());

        return PayoutResource::collection($payout);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePayoutRequest $request)
    {
        $this->authorize('create', Payout::class);
        $payout = $this->payoutService->store($request->user(), $request->validated());

        return new PayoutResource($payout);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payout $payout)
    {
        $this->authorize('view', $payout);

        return new PayoutResource($payout);
    }
}
