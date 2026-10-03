<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSensorReadingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'people_count' => ['required', 'integer', 'min:0'],
            'cubicle_1_occupied' => ['nullable', 'boolean'],
            'cubicle_2_occupied' => ['nullable', 'boolean'],
            'tcs_red' => ['required', 'integer', 'between:0,65535'],
            'tcs_green' => ['required', 'integer', 'between:0,65535'],
            'tcs_blue' => ['required', 'integer', 'between:0,65535'],
            'tcs_clear' => ['required', 'integer', 'between:0,65535'],
            'water_state' => ['nullable', Rule::in(['dry', 'clear_water', 'muddy_water', 'uncalibrated'])],
            'mq135_raw' => ['required', 'integer', 'min:0'],
            'mq135_state' => ['nullable', Rule::in(['normal', 'elevated', 'uncalibrated'])],
        ];
    }
}
