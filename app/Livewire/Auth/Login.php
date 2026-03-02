<?php

namespace App\Livewire\Auth;

use App\Services\AuthService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.auth')]
#[Title('Sign In — Clinix')]
class Login extends Component
{
    public string $email    = '';
    public string $password = '';
    public bool   $remember = false;

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function login(AuthService $authService): void
    {
        $this->validate();

        try {
            $user     = $authService->login($this->email, $this->password, $this->remember);
            $redirect = $authService->redirectAfterLogin($user);
            $this->redirect($redirect, navigate: true);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('email', $e->getMessage());
            $this->reset('password');
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}