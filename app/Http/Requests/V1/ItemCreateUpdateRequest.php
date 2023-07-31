<?php

namespace App\Http\Requests\V1;

use App\Helpers\CommonHelper;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ItemCreateUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules =  [
            'name' => 'required',
            'uom_id' => [
                'required',
                Rule::exists('unit_of_measurements','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                }),
            ],
            'item_category_id' => [
                'required',
                Rule::exists('item_categories','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                }),
            ],
            'price' => 'required',
            'status' => 'required|numeric|lte:1'
        ];
        if (request()->has('is_vendor')) {
            $rules['is_vendor'] = 'required|numeric';
            $rules['vendor_id'] = [
                'required',
                Rule::exists('entitymst','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                    $query->where('entity_type', CommonHelper::getConfigValue('entity_type.vendor'));
                }),
            ];
        }

        if (request()->hasFile('image')) {
            $rules['image'] = 'required|max:2048|mimes:jpg,png,jpeg';
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => 'Please enter name',
            'uom_id.required' => 'Please enter unit of measurement id',
            'uom_id.exists' => 'Unit of Measurement'.__('messages.validation.not_found'),
            'item_category_id.required' => 'Please enter item category id',
            'item_category_id.exists' => 'Item Category'.__('messages.validation.not_found'),
            'price.required' => 'Please email price',
            'status.required' => __('messages.validation.status'),
            'status.numeric' => 'Status' . __('messages.validation.must_numeric'),
            'status.lte' => __('messages.validation.status_lte'),
        ];

        if(request()->has('is_vendor')){
            $messages['is_vendor.required'] = 'Please enter is vendor';
            $messages['is_vendor.numeric'] = 'Is vendor value must be numeric';
            $messages['vendor_id.required'] = 'Please enter vendor id';
            $messages['vendor_id.exists'] = 'Vendor not found';
        }

        if (request()->hasFile('image')) {
            $messages['image.required'] = __('messages.validation.image');
            $messages['image.max'] =  __('messages.validation.image-max');
            $messages['image.mimes'] = __('messages.validation.image-mimes');
        }

        
        return $messages;
    }
}
