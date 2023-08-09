<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest
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
            'schedule' => 'required|array',
            'schedule.*.work_day' => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'schedule.*.start_time' => 'required|date_format:H:i:s',
            'schedule.*.end_time' => 'required|date_format:H:i:s|after:schedule.*.start_time',
            'schedule.*.status' => 'required|in:1,0',
        ];
        return $rules;
    }

    public function messages(): array
    {
        return [
            'schedule.required' => 'The schedule field is required.',
            'schedule.array' => 'The schedule must be an array.',
            'schedule.*.work_day.required' => 'Each day must have a work day.',
            'schedule.*.work_day.in' => 'The selected schedule work day is invalid, schedule work day must be in mon,tue,wed,thu,fri,sat or sun',
            'schedule.*.start_time.required' => 'Each day must have a start time.',
            'schedule.*.start_time.date_format' => 'Each day start time must be in the format H:i:s.',
            'schedule.*.end_time.required' => 'Each day must have an end time.',
            'schedule.*.end_time.date_format' => 'Each day end time must be in the format H:i:s.',
            'schedule.*.end_time.after' => 'The schedule start time field must match the format H:i:s',
            'schedule.*.status.required' => 'Each day must have a status.',
            'schedule.*.status.in' => 'Each day status must be either 0 or 1.',
        ];
    }
}
