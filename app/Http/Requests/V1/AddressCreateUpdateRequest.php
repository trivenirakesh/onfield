<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddressCreateUpdateRequest extends FormRequest
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
            'address_type_id' => 'required|exists:address_types,id,deleted_at,NULL',
            'address' => 'required|max:1000',
            'state_id' => 'required|exists:states,id,deleted_at,NULL',
            'city' => 'required|max:200',
            'pincode' => 'required|numeric|digits_between:3,10',
            'longitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
            'notes' => 'nullable|max:200',
            'status' => ['nullable', Rule::in([1, 0])],
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [];

        return $messages;
    }
}
