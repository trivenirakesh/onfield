<?php

namespace App\Http\Requests\V1\Api;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class EngineerRegisterRequest extends FormRequest
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
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'email' => 'required|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile,NULL,id,deleted_at,NULL',
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'image' => 'required|max:2048|mimes:jpg,png,jpeg',
            'addressproof' => 'required|max:2048|mimes:jpg,png,jpeg',
            'idproof' => 'required|max:2048|mimes:jpg,png,jpeg',
            'resume' => 'required|max:2048|mimes:pdf',
            'state_id' => 'required|exists:states,id',
            'city' => 'required|max:200',
            'address' => 'required|max:255',
            'skills' => 'required|array',
            'skills.*' => [
                'required',
                Rule::exists('skills', 'id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                }),
            ],

        ];
        return $rules;
    }

    public function messages(): array
    {
        return [
            'skills.*.exists' => 'The selected skill is invalid.',
        ];
    }
}
