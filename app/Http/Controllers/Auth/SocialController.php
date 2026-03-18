<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists by google_id
            $user = User::where('google_id', $googleUser->id)->first();

            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $googleUser->email)->first();

                if ($user) {
                    // Link to existing account
                    $user->update([
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken,
                        'avatar' => $googleUser->avatar,
                    ]);
                } else {
                    // Create new patient user
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'google_token' => $googleUser->token,
                        'google_refresh_token' => $googleUser->refreshToken,
                        'avatar' => $googleUser->avatar,
                        'password' => bcrypt(str()->random(24)),
                        'user_type' => 'patient', // Explicitly for patients as requested
                        'email_verified_at' => now(),
                    ]);
                    
                    $user->assignRole('patient');
                }
            } else {
                // Update tokens
                $user->update([
                    'google_token' => $googleUser->token,
                    'google_refresh_token' => $googleUser->refreshToken,
                    'avatar' => $googleUser->avatar,
                ]);
            }

            Auth::login($user);
            $user->update(['last_login_at' => now()]);

            if (!$user->email_verified_at) {
                $user->generateOtp();
                $user->notify(new \App\Notifications\OtpNotification($user->otp));
                return redirect()->route('verify-otp');
            }

            // If patient profile is missing, redirect to completion form
            if ($user->isPatient() && !$user->patient) {
                return redirect()->route('patient.complete-profile');
            }

            return redirect()->route($user->getDashboardRoute());

        } catch (Exception $e) {
            \Log::error('Google Login Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Something went wrong with Google Login: ' . $e->getMessage());
        }
    }
}
