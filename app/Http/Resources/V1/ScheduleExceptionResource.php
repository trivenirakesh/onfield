<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Helpers\CommonHelper;

class ScheduleExceptionResource extends JsonResource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'start_time' => $this->start_time,
            'end_time' =>  $this->end_time,
            'user_id' =>  $this->user_id,
            'all_day' =>  $this->all_day,
            'all_day_text' => $this->all_day == 1 ? 'Yes' : 'No',
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
