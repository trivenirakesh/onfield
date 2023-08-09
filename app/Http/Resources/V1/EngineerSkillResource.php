<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class EngineerSkillResource extends JsonResource
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
            'skill_id' => $this->skill_id,
            'user_id' => $this->skill_id,
            'skill' =>  $this->whenLoaded('skill'),
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
