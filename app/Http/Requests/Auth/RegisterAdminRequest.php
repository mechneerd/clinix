<?php

namespace App\Http\Requests\Auth;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Personal
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'              => ['required', 'string', 'max:20'],
            'password'           => ['required', 'confirmed', new StrongPassword()],

            // Professional
            'license_number'     => ['required', 'string', 'max:100'],
            'specialty'          => ['required', 'string', 'max:150'],
            'years_of_experience'=> ['required', 'integer', 'min:0', 'max:60'],

            // Profile
            'gender'             => ['required', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'date_of_birth'      => ['required', 'date', 'before:-18 years'],
            'address'            => ['nullable', 'string', 'max:500'],

            // Terms
            'terms'              => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'            => 'Full name is required.',
            'email.unique'             => 'This email is already registered.',
            'license_number.required'  => 'Medical license number is required.',
            'specialty.required'       => 'Please enter your specialty.',
            'date_of_birth.before'     => 'You must be at least 18 years old.',
            'terms.accepted'           => 'You must accept the terms and conditions.',
        ];
    }
}