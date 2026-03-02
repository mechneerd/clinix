<?php

namespace App\Http\Requests\Auth;

use App\Rules\StrongPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterPatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'        => ['required', 'string', 'max:20'],
            'password'     => ['required', 'confirmed', new StrongPassword()],
            'gender'       => ['required', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'date_of_birth'=> ['required', 'date', 'before:today'],
            'blood_group'  => ['nullable', 'string', 'max:10'],
            'address'      => ['nullable', 'string', 'max:500'],
            'terms'        => ['required', 'accepted'],
        ];
    }
}