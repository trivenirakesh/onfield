<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class AdminProfileRequest extends FormRequest
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
        $rules = [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'mobile' => 'required|numeric|digits:10|unique:users,mobile,' . $this->id . ',id,deleted_at,NULL',
            'email' => 'required|email|unique:users,email,' . $this->id . ',id,deleted_at,NULL'
        ];
        return $rules;
    }
}
