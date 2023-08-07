<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

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
            'id' => $this->id,
            'user_id' => $this->user_id,
            'address_type_id' => $this->address_type_id,
            'address_type' => $this->whenLoaded('address_type'),
            'address' => $this->address,
            'state_id' => $this->state_id,
            'state' =>  $this->whenLoaded('state'),
            'city' => $this->city,
            'pincode' => $this->pincode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'status' => $this->status,
            'status_text' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
