<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class UpdateProfileRequest extends FormRequest
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
            'first_name' =>  ['required', 'max:100'],
            'last_name'  =>  ['required', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:1024'],
        ];
    }
}
