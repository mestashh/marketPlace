<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $uuid
 * @property mixed $conversation
 * @property mixed $text
 * @property mixed $created_at
 * @property mixed $user
 */
class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $timezone = $user->timezone;

        return [
            'message_uuid' => $this->uuid,
            'conversation_uuid' => $this->conversation->uuid,
            'user_uuid' => $this->user->uuid,
            'text' => $this->text,
            'date' => $this->created_at,
        ];
    }
}
