<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    private string $id;
    private string $notifiable_type;
    private string $type;
    private string $data;
    private string $read_at;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'notifiable_type' => $this->notifiable_type,
            'type' => $this->type,
            'data' => $this->data,
            'read_at' => $this->read_at,
        ];
    }
}
