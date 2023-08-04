<?php

namespace App\Http\Requests\V1\Api;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class ClientRegisterRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'city' => 'required_with:state|max:200',
            'state' => 'required_with:city|exists:states,id',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile,NULL,id,deleted_at,NULL',
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ]
            // 'password' => ['required', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*#?&]/']
        ];
        return $rules;
    }
}
