<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payout\StorePayoutRequest;
use App\Http\Resources\PayoutResource;
use App\Models\Payout;
use Illuminate\Http\Request;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Payout::class);
        $user = $request->user();
        if ($user->isAdmin()) {
            $payout = Payout::query()->paginate(20);
        } elseif ($user->isSeller()) {
            $payout = Payout::where('seller_id', $user->seller->id)->get();
        }

        return PayoutResource::collection($payout);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePayoutRequest $request)
    {
        $this->authorize('create', Payout::class);
        $user = $request->user()->seller->id;
        $data = $request->validated();
        $payout = Payout::create([
            'seller_id' => $user,
            'amount' => $data['amount'],
        ]);
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
