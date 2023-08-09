<?php

namespace App\Http\Requests\V1\Api;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class EngineerProfileRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:users,email,' . $id . ',id,deleted_at,NULL',
            'image' => 'nullable|max:2048|mimes:jpg,png,jpeg',
            'addressproof' => 'nullable|max:2048|mimes:jpg,png,jpeg',
            'idproof' => 'nullable|max:2048|mimes:jpg,png,jpeg',
            'resume' => 'nullable|max:2048|mimes:pdf',
        ];
        return $rules;
    }

    public function messages(): array
    {
        return [];
    }
}
