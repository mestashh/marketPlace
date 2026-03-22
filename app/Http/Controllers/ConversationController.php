<?php

namespace App\Http\Controllers;

use App\Enums\ConversationStatusEnum;
use App\Http\Requests\Conversation\StoreConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\ShopOrder;
use Symfony\Component\HttpFoundation\Request;

class ConversationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Conversation::class);
        if ($request->user()->isAdmin()) {
            $conversation = Conversation::query()->paginate(20);
        } elseif ($request->user()->isSeller()) {
            $conversation = Conversation::where('seller_id', $request->user()->seller->id)->get();
        } else {
            $conversation = Conversation::where('user_id', $request->user()->id)->get();
        }

        return ConversationResource::collection($conversation);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConversationRequest $request, Order $order)
    {
        $this->authorize('create', [Conversation::class, $order]);
        $data = $request->validated();
        $user = $request->user();
        $shopOrder = ShopOrder::findOrFail($data['shop_order_id']);
        $seller = $shopOrder->shop->seller->id;
        $conversation = Conversation::create([
            'shop_order_id' => $data['shop_order_id'],
            'user_id' => $user->id,
            'seller_id' => $seller,
            'status' => ConversationStatusEnum::OPEN->value,
        ]);

        return new ConversationResource($conversation);
    }

    /**
     * Display the specified resource.
     */
    public function show(Conversation $conversation)
    {
        $this->authorize('view', $conversation);

        return new ConversationResource($conversation);
    }

    public function callAdmin(Conversation $conversation)
    {
        $this->authorize('callAdmin', $conversation);

        $conversation->update([
            'status' => ConversationStatusEnum::WAIT_FOR_ADMIN->value,
        ]);

        return new ConversationResource($conversation);
    }

    public function close(Conversation $conversation)
    {
        $this->authorize('close', $conversation);
        $conversation->update([
            'status' => ConversationStatusEnum::CLOSED->value,
        ]);

        return new ConversationResource($conversation);
    }
}
