<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AttendanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * You can add more complex authorization logic here if needed.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => [
                'required',
                'exists:employees,id',
            ],
            'check_in' => [
                'required',
                'date',
                'before_or_equal:now',
            ],
            'check_out' => [
                'nullable',
                'date',
                'after:check_in',
            ],
        ];
    }

    /**
     * Custom messages for validation errors.
     * This method helps provide user-friendly error messages.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'The employee ID is required.',
            'employee_id.exists' => 'The employee must exist in the system.',
            'check_in.required' => 'The check-in time is required.',
            'check_in.before_or_equal' => 'Check-in time cannot be in the future.',
            'check_out.after' => 'The check-out time must be after the check-in time.',
        ];
    }

    /**
     * Custom attribute names for better readability.
     * Useful for generating error messages with meaningful attribute names.
     */
    public function attributes(): array
    {
        return [
            'employee_id' => 'employee',
            'check_in' => 'check-in time',
            'check_out' => 'check-out time',
        ];
    }
}
