<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface AuthRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function findById(int $id): ?User;

    public function createAdmin(array $data): User;

    public function createPatient(array $data): User;

    public function updateUser(int $id, array $data): User;

    public function createOrUpdateProfile(User $user, array $profileData): void;
}