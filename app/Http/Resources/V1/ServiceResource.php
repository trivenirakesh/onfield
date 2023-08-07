<?php

namespace App\Http\Resources\V1;

use App\Helpers\CommonHelper;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'status' => $this->status,
            'status_text' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'service_category_id' => $this->service_category_id,
            'service_category' => $this->whenLoaded('service_category'),
            'image' => CommonHelper::getUploadUrl(Service::class, $this->id, Service::FOLDERNAME),
            'commission' => $this->commission,
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
