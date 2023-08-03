<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitOfMeasurementCreateUpdateRequest extends FormRequest
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
            'factor' => 'required|numeric|between:0,99.99',
            'status' => ['required',Rule::in([1,0])],
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'name.required' =>  __('messages.validation.name'),
            'name.max' => __('messages.validation.max'),
            'factor.required' => __('messages.validation.factor_required'),
            'factor.numeric' => __('messages.validation.factor_numeric'),
            'status.required' => __('messages.validation.status'),
            'status.in' => __('messages.validation.status_in'),
        ];
        
        return $messages;
    }
}
