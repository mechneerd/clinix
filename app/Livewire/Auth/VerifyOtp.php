<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class VerifyOtp extends Component
{
    public $otp;

    protected $rules = [
        'otp' => 'required|digits:6',
    ];

    public function mount()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->email_verified_at) {
            return redirect()->route(Auth::user()->getDashboardRoute());
        }

        if (!Auth::user()->otp) {
            Auth::user()->generateOtp();
            Auth::user()->notify(new \App\Notifications\OtpNotification(Auth::user()->otp));
        }
    }

    public function verify()
    {
        $this->validate();

        $user = Auth::user();

        if ($user->isOtpValid($this->otp)) {
            $user->email_verified_at = now();
            $user->clearOtp();
            
            session()->flash('success', 'Email verified successfully!');
            
            return redirect()->route($user->getDashboardRoute());
        }

        $this->addError('otp', 'Invalid or expired OTP.');
    }

    public function resend()
    {
        $user = Auth::user();
        $user->generateOtp();
        $user->notify(new \App\Notifications\OtpNotification($user->otp));

        session()->flash('success', 'A new OTP has been sent to your email.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.auth.verify-otp')->layout('components.layouts.app');
    }
}
