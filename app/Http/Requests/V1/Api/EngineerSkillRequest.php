<?php

namespace App\Http\Requests\V1\Api;

use App\Helpers\CommonHelper;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class EngineerSkillRequest extends FormRequest
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
