<?php
// app/Http/Requests/Staff/CreateStaffRequest.php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class CreateStaffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create_staff', $this->route('clinic'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'phone:AUTO', 'unique:users,phone'],
            'role_id' => ['required', 'exists:clinic_roles,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'employee_id' => ['nullable', 'string', 'max:50'],
            'qualification' => ['nullable', 'string', 'max:255'],
            'specializations' => ['nullable', 'string', 'max:500'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'joining_date' => ['required', 'date'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'employment_type' => ['required', 'in:full_time,part_time,contract,visiting'],
            'biography' => ['nullable', 'string', 'max:2000'],
            'consultation_fee' => ['nullable', 'numeric', 'min:0'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ];
    }
}