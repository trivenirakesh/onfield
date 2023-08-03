<?php

namespace App\Http\Resources\V1;

use App\Helpers\CommonHelper;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceCategoryResource extends JsonResource
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
            'image' => CommonHelper::getUploadUrl(ServiceCategory::class,$this->id,ServiceCategory::FOLDERNAME),
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
