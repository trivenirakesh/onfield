<?php

namespace App\Http\Requests\V1\Api;

use App\Helpers\CommonHelper;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        $id = auth()->id();
        $rules = [
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'email' => 'required|email|unique:users,email,' . $id . ',id,deleted_at,NULL',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile,' . $id . ',id,deleted_at,NULL',
        ];
        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
