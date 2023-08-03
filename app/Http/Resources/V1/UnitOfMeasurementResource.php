<?php

namespace App\Http\Resources\V1;

use App\Helpers\CommonHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitOfMeasurementResource extends JsonResource
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
            'name' => $this->name,
            'description' => ($this->description != NULL) ? $this->description : '',
            'factor' => $this->factor,
            'status' => $this->status,
            'status_text' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
