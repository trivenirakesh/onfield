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
            'uom_id' => $this->unit_of_measurement_id,
            'unit_of_measurement' => isset($this->unitOfMeasurement['name']) ? $this->unitOfMeasurement['name'] : '',
            'item_category_id' => $this->item_category_id,
            'item_category' => isset($this->itemCategory['name']) ? $this->itemCategory['name'] : '',
            'is_vendor' => !empty($this->is_vendor) ? true : false,
            'vendor' => isset($this->user['first_name']) ? $this->user['first_name'].' '.$this->user['last_name'] : '',
            'user_id' => !empty($this->user_id) ? $this->user_id : '',
            'status' => $this->status,
            'status_text' => ($this->status == 1 ? 'Active' : 'Deactive'),
            'image' => CommonHelper::getUploadUrl(Item::class,$this->id,Item::FOLDERNAME),
            'created_at' => CommonHelper::getConvertedDateTime($this->created_at)
        ];
    }
}
