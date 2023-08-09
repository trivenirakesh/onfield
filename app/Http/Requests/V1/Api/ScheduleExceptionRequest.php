<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleExceptionRequest extends FormRequest
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
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'all_day' => 'required|boolean',
            'start_time' => [
                'required_unless:all_day,1',
                'nullable',
                'date_format:H:i:s',
            ],
            'end_time' => [
                'required_unless:all_day,1',
                'nullable',
                'date_format:H:i:s',
                'after:start_time',
            ],
        ];
        return $rules;
    }

    public function messages(): array
    {
        return [
            'end_time.after' => 'The end time field must be a time after start time.'
        ];
    }
}
