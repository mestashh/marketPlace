<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $country
 * @property mixed $city
 * @property mixed $street
 * @property mixed $house
 * @property mixed $description
 * @property mixed $phone
 */
class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'country' => $this->country,
            'city' => $this->city,
            'street' => $this->street,
            'house' => $this->house,
            'description' => $this->description,
            'phone' => $this->phone,
        ];
    }
}
