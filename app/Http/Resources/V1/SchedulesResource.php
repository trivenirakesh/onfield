<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class SchedulesResource extends JsonResource
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
            'work_day' => $this->work_day,
            'start_time' => $this->start_time,
            'end_time' =>  $this->end_time,
            'user_id' =>  $this->user_id,
            'status' =>  $this->status,
            'status_text' => $this->status == 1 ? 'Active' : 'Deactive',
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
