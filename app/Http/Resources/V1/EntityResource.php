<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class EntityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->first_name.' '.$this->last_name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    } 
}
