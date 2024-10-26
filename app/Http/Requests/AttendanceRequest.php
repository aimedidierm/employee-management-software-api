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
        ];
    }

    /**
     * Custom messages for validation errors.
     * This method helps provide user-friendly error messages.
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => trans('The employee ID is required.'),
            'employee_id.exists' => trans('The employee must exist in the system.'),
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
        ];
    }
}
