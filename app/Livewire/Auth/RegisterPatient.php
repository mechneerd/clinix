<?php

namespace App\Livewire\Auth;

use App\Rules\StrongPassword;
use App\Services\AuthService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rule;

#[Layout('layouts.auth')]
#[Title('Register as Patient — Clinix')]
class RegisterPatient extends Component
{
    public string $name                  = '';
    public string $email                 = '';
    public string $phone                 = '';
    public string $password              = '';
    public string $password_confirmation = '';
    public string $gender                = '';
    public string $date_of_birth         = '';
    public string $blood_group           = '';
    public string $address               = '';
    public bool   $terms                 = false;

    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'email'        => ['required', 'email', 'unique:users,email'],
            'phone'        => ['required', 'string', 'max:20'],
            'password'     => ['required', 'min:8', 'confirmed', new StrongPassword()],
            'gender'       => ['required', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'date_of_birth'=> ['required', 'date', 'before:today'],
            'blood_group'  => ['nullable', 'string'],
            'terms'        => ['accepted'],
        ];
    }

    public function register(AuthService $authService): void
    {
        $this->validate();

        $authService->registerPatient([
            'name'         => $this->name,
            'email'        => $this->email,
            'phone'        => $this->phone,
            'password'     => $this->password,
            'gender'       => $this->gender,
            'date_of_birth'=> $this->date_of_birth,
            'blood_group'  => $this->blood_group,
            'address'      => $this->address,
        ]);

        $this->redirect(route('patient.dashboard'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register-patient');
    }
}