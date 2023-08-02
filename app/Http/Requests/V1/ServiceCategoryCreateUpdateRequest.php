<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceCategoryCreateUpdateRequest extends FormRequest
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
            'name' => 'required|max:200',
            'status' => ['required',Rule::in([1,0])],
        ];

        $rules['image'] = ($this->id != null && !request()->hasFile('image')) ? '' : 'required|max:2048|mimes:jpg,png,jpeg';
        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' => __('messages.validation.name'),
            'name.max' => __('messages.validation.max'),
            'status.required' => __('messages.validation.status'),
            'status.in' => __('messages.validation.status_in'),
            'image.required' => __('messages.validation.image'),
            'image.max' =>  __('messages.validation.image-max'),
            'image.mimes' => __('messages.validation.image-mimes'),
        ];
        
        return $messages;
    }
}
