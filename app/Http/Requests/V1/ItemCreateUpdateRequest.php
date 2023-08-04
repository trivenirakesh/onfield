<?php

namespace App\Http\Requests\V1;

use App\Helpers\CommonHelper;
use App\Models\User;
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
            'unit_of_measurement_id' => [
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
            $rules['user_id'] = [
                'required',
                Rule::exists('users','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                    $query->where('user_type', User::USERVENDOR);
                }),
            ];
        }

        $rules['image'] = (!empty($this->id) && !request()->hasFile('image')) ? '' : 'required|max:2048|mimes:jpg,png,jpeg';

        return $rules;
    }

}
