<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SignUpRequest extends FormRequest
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
        $rules = [
            'entity_type' => ['required',Rule::in([1,2])],
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'email' => 'required|email|unique:entitymst,email,NULL,id,deleted_at,NULL',
            'mobile' => 'required|numeric|digits:10|unique:entitymst,mobile,NULL,id,deleted_at,NULL',
            'password' => [ 'required', 'min:8','regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/','regex:/[@$!%*#?&]/']
        ];

        return $rules;
    }

    public function messages() : array
    {
        $messages = [
            'first_name.required' => __('messages.validation.first_name'),
            'first_name.max' => __('messages.validation.max'),
            'last_name.required' => __('messages.validation.last_name'),
            'last_name.max' => __('messages.validation.max'),
            'email.required' => __('messages.validation.email'),
            'email.email' => __('messages.validation.email_email'),
            'email.unique' => __('messages.validation.email_unique'),
            'mobile.required' => __('messages.validation.mobile'),
            'mobile.numeric' => 'Mobile' . __('messages.validation.must_numeric'),
            'mobile.digits' => __('messages.validation.mobile_digits'),
            'mobile.unique' => __('messages.validation.mobile_unique'),
            'entity_type.required' => __('messages.validation.entity_type'),
            'entity_type.in' => __('messages.validation.entity_type_in'),
            'password.required' => __('messages.validation.password'),
            'password.min' => __('messages.validation.new_password_min'),
            'password.regex' => __('messages.validation.strong_password'),
        ];
        
        return $messages;
    }
}
