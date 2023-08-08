<?php

namespace App\Http\Requests\V1\Api;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
            'service_category_id' => 'required|exists:service_categories,id,deleted_at,NULL',
            'booking_start_datetime' => 'required|date_format:Y-m-d H:i:s',
            'booking_end_datetime' => [
                'required',
                'date',
                'after:booking_start_datetime',
                'date_format:Y-m-d H:i:s',
                function ($attribute, $value, $fail) {
                    $start = $this->input('booking_start_datetime');
                    $startDatetime = \Carbon\Carbon::parse($start);
                    $endDatetime = \Carbon\Carbon::parse($value);
                    if ($startDatetime->diffInMinutes($endDatetime) > 60) {
                        $fail('The :attribute should have a maximum 1-hour gap from the start datetime.');
                    }
                },
            ],
            'address_id' => 'required|exists:addresses,id,deleted_at,NULL',
            // 'services' => 'required|array|min:1',
            // 'services.*.id' => 'required|exists:services,id,deleted_at,NULL',
            // 'services.*.qty' => 'required|integer|min:1',
            'payment_type' => 'required|in:digital,cash',
        ];
        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
