<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginPage extends Component
{
    public $loginType = 'clinic'; // clinic, patient
    public $userType = 'admin'; // admin, staff (for clinic login)
    public $email = '';
    public $password = '';
    public $remember = false;

    protected function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function updatedLoginType()
    {
        $this->reset('userType', 'email', 'password');
    }

    public function login()
    {
        $this->validate();

        $key = strtolower($this->email) . '|' . request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages([
                'email' => 'Too many login attempts. Please try again in ' . RateLimiter::availableIn($key) . ' seconds.',
            ]);
        }

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            RateLimiter::clear($key);

            $user = Auth::user();

            // Validate role based on login type
            if ($this->loginType === 'clinic') {
                if ($this->userType === 'admin' && !$user->hasAnyRole(['super-admin', 'clinic-admin'])) {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => 'Invalid credentials for clinic admin login.',
                    ]);
                }

                if ($this->userType === 'staff' && !$user->isStaff()) {
                    Auth::logout();
                    throw ValidationException::withMessages([
                        'email' => 'Invalid credentials for staff login.',
                    ]);
                }
            }
            elseif ($this->loginType === 'patient' && !$user->isPatient()) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Invalid credentials for patient login.',
                ]);
            }

            $user->update(['last_login_at' => now()]);

            if (!$user->email_verified_at) {
                $user->generateOtp();
                $user->notify(new \App\Notifications\OtpNotification($user->otp));
                return redirect()->route('verify-otp');
            }

            return redirect()->route($user->getDashboardRoute());
        }

        RateLimiter::hit($key);

        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function render()
    {
        return view('livewire.auth.login-page')
            ->layout('components.layouts.guest');
    }
}