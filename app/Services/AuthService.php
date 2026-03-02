<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected AuthRepositoryInterface $authRepository
    ) {}

    public function login(string $email, string $password, bool $remember = false): User
    {
        if (!Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        /** @var User $user */
        $user = Auth::user();

        session()->regenerate();

        return $user;
    }

    public function registerAdmin(array $data): User
    {
        $user = $this->authRepository->createAdmin($data);

        $this->authRepository->createOrUpdateProfile($user, [
            'user_type'          => 'admin',
            'gender'             => $data['gender'] ?? null,
            'date_of_birth'      => $data['date_of_birth'] ?? null,
            'license_number'     => $data['license_number'] ?? null,
            'specialty'          => $data['specialty'] ?? null,
            'years_of_experience'=> $data['years_of_experience'] ?? null,
            'address'            => $data['address'] ?? null,
        ]);

        Auth::login($user);
        session()->regenerate();

        return $user;
    }

    public function registerPatient(array $data): User
    {
        $user = $this->authRepository->createPatient($data);

        $this->authRepository->createOrUpdateProfile($user, [
            'user_type'     => 'patient',
            'gender'        => $data['gender'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'blood_group'   => $data['blood_group'] ?? null,
            'address'       => $data['address'] ?? null,
        ]);

        Auth::login($user);
        session()->regenerate();

        return $user;
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    public function redirectAfterLogin(User $user): string
    {
        // Super Admin → platform dashboard
        if ($user->hasRole('super_admin')) {
            return route('super-admin.dashboard');
        }

        // Admin (Healthcare Provider)
        if ($user->isAdmin()) {
            if (!$user->hasActiveSubscription()) {
                return route('subscription.select');
            }
            return route('admin.dashboard');
        }

        // Patient
        return route('patient.dashboard');
    }
}
