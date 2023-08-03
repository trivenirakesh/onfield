<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResetPasswordRequest extends FormRequest
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
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'otp' =>  ['required', 'digits:6'],
            'password'  =>  ['required', 'min:6'],
            'password_confirmation' =>  ['required', 'same:password'],
        ];
    }
}
