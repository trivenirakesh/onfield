<?php

namespace App\Http\Resources\V1;

use App\Helpers\CommonHelper;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'price' => $this->price,
            'unit_of_measurement' => isset($this->unitOfMeasurement['name']) ? $this->unitOfMeasurement['name'] : '',
            'item_category' => isset($this->itemCategory['name']) ? $this->itemCategory['name'] : '',
            'is_vendor' => $this->is_vendor == 1 ? true : false,
            'vendor' => isset($this->entity['first_name']) ? $this->entity['first_name'].' '.$this->entity['last_name'] : '',
            'status' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'image' => CommonHelper::getUploadUrl(Item::class,$this->id,Item::FOLDERNAME),
            'image22' => !empty($this->image) ? CommonHelper::getImageUrl($this->image['file'],Item::FOLDERNAME,0) : '',
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
