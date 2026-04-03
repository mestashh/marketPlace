<?php

namespace App\Http\Controllers;

use App\Enums\ConversationStatusEnum;
use App\Exceptions\Conversation\ConversationCallAdminException;
use App\Http\Requests\Conversation\StoreConversationRequest;
use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use App\Models\Order;
use App\Models\ShopOrder;
use App\Services\ConversationService;
use Symfony\Component\HttpFoundation\Request;

class ConversationController extends Controller
{
    public function __construct(private readonly ConversationService $conversationService) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Conversation::class);
        $conversation = $this->conversationService->index($request->user());

        return ConversationResource::collection($conversation);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConversationRequest $request, Order $order)
    {
        $this->authorize('create', [Conversation::class, $order]);
        $conversation = $this->conversationService->store($request->user(), $request->validated());

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

    /**
     * @throws ConversationCallAdminException
     */
    public function callAdmin(Conversation $conversation)
    {
        $this->authorize('callAdmin', $conversation);
        $this->conversationService->callAdmin($conversation);

        return response()->json(['message' => 'Admin called'], 200);
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
