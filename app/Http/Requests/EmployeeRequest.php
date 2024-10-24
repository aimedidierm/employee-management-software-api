<?php

namespace App\Http\Requests;

use App\Enums\EmployeePosition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use ReflectionClass;

class EmployeeRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('employees')->ignore($this->employee)
            ],
            'position' => [
                'required',
                'string',
                'max:255',
                Rule::in(array_values((new ReflectionClass(EmployeePosition::class))->getConstants()))
            ],
            'phone_number' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Employee name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already taken.',
        ];
    }
}
