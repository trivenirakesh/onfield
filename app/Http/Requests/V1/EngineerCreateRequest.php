<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class EngineerCreateRequest extends FormRequest
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
        return [
            'user_type' => ['required',Rule::in([1])],
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile,NULL,id,deleted_at,NULL',
            'password' => ['required',Password::min(8)->mixedCase()->numbers()->symbols()]
        ];
    }
}
