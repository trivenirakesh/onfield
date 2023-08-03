<?php

namespace App\Http\Requests\V1;

use App\Helpers\CommonHelper;
use App\Models\Users;
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
            'price' => 'required|numeric',
            'status' => ['required',Rule::in([1,0])],
        ];
        if (request()->has('is_vendor')) {
            $rules['is_vendor'] = 'required|numeric';
            $rules['vendor_id'] = [
                'required',
                Rule::exists('Users','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                    $query->where('entity_type', Users::ENTITYVENDOR);
                }),
            ];
        }

        $rules['image'] = (!empty($this->id) && !request()->hasFile('image')) ? '' : 'required|max:2048|mimes:jpg,png,jpeg';

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => __('messages.validation.name'),
            'uom_id.required' => __('messages.item.uom_id_required'),
            'uom_id.exists' => 'Unit of Measurement'.__('messages.validation.not_found'),
            'item_category_id.required' => __('messages.item.item_category_id_required'),
            'item_category_id.exists' => 'Item Category'.__('messages.validation.not_found'),
            'price.required' => __('messages.item.price_required'),
            'status.required' => __('messages.validation.status'),
            'status.in' => __('messages.validation.status_in'),
            'image.required' => __('messages.validation.image'),
            'image.max' =>  __('messages.validation.image-max'),
            'image.mimes' => __('messages.validation.image-mimes'),
        ];

        if(request()->has('is_vendor')){
            $messages['is_vendor.required'] = __('messages.item.is_vendor_required');
            $messages['is_vendor.numeric'] = 'Is vendor'.__('messages.validation.must_numeric');
            $messages['vendor_id.required'] = __('messages.item.vendor_id_required');
            $messages['vendor_id.exists'] = 'Vendor'.__('messages.validation.not_found');
        }

        return $messages;
    }
}
