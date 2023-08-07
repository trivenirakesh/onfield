<?php

namespace App\Http\Requests\V1;

use App\Helpers\CommonHelper;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserCreateUpdateRequest extends FormRequest
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
            'user_type' => ['required',Rule::in([0,1,2])],
            'first_name' => 'required|max:200',
            'last_name' => 'required|max:200',
            'status' => ['required',Rule::in([1,0])],
            'role_id' => [
                'required',
                Rule::exists('roles','id')->where(function ($query) {
                    $query->where('status', CommonHelper::getConfigValue('status.active'));
                }),
            ]
        ];

        if ($this->id != null) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->id . ',id,deleted_at,NULL';
            $rules['mobile'] = 'required|numeric|digits:10|unique:users,mobile,' . $this->id . ',id,deleted_at,NULL';
        } else {
            $rules['email'] = 'required|email|unique:users,email,NULL,id,deleted_at,NULL';
            $rules['mobile'] = 'required|numeric|digits:10|unique:users,mobile,NULL,id,deleted_at,NULL';
        }
        
        if (request()->has('password') || request()->has('id') ) {
            $rules['password'] = request()->has('id') && request()->id>0 ? '': [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ];
        }
        return $rules;
    }

}
