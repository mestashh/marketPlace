<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Conversation;
use App\Models\Message;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Conversation $conversation)
    {
        $this->authorize('viewAny', [Message::class, $conversation]);
        $message = Message::where('conversation_id', $conversation->id)->get();

        return MessageResource::collection($message);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMessageRequest $request, Conversation $conversation)
    {
        $this->authorize('create', [Message::class, $conversation]);
        $data = $request->validated();
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'user_id' => $request->user()->id,
            'text' => $data['text'],
        ]);

        return new MessageResource($message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message, Conversation $conversation)
    {
        $this->authorize('view', [Message::class, $conversation]);
        return new MessageResource($message);
    }
}
