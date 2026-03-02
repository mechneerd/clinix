<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\UserProfile;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function createAdmin(array $data): User
    {
        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'password'          => Hash::make($data['password']),
            'registration_type' => 'admin',
        ]);

        $user->assignRole('admin');

        return $user;
    }

    public function createPatient(array $data): User
    {
        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'password'          => Hash::make($data['password']),
            'registration_type' => 'patient',
        ]);

        $user->assignRole('patient');

        return $user;
    }

    public function updateUser(int $id, array $data): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user->fresh();
    }

    public function createOrUpdateProfile(User $user, array $profileData): void
    {
        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );
    }
}