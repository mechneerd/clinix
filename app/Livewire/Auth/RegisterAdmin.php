<?php

namespace App\Livewire\Auth;

use App\Rules\StrongPassword;
use App\Services\AuthService;
use App\Services\DiceBearService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.auth')]
#[Title('Register as Provider — Clinix')]
class RegisterAdmin extends Component
{
    // Step tracking
    public int $step = 1;
    public int $totalSteps = 3;

    // Step 1 – Account
    public string $name     = '';
    public string $email    = '';
    public string $phone    = '';
    public string $password = '';
    public string $password_confirmation = '';

    // Step 2 – Professional
    public string $license_number      = '';
    public string $specialty           = '';
    public string $years_of_experience = '';

    // Step 3 – Personal + Terms
    public string $gender        = '';
    public string $date_of_birth = '';
    public string $address       = '';
    public bool   $terms         = false;

    // Avatar preview
    public ?string $avatarPreview = null;
    public string $avatarStyle = 'adventurer-neutral';

    public function stepOneRules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'phone'    => ['required', 'string', 'max:20'],
            'password' => ['required', 'min:8', 'confirmed', new StrongPassword()],
        ];
    }

    public function stepTwoRules(): array
    {
        return [
            'license_number'      => ['required', 'string', 'max:100'],
            'specialty'           => ['required', 'string', 'max:150'],
            'years_of_experience' => ['required', 'integer', 'min:0'],
        ];
    }

    public function stepThreeRules(): array
    {
        return [
            'gender'        => ['required', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'date_of_birth' => ['required', 'date', 'before:-18 years'],
            'terms'         => ['accepted'],
        ];
    }

    /**
     * Generate avatar preview based on name
     */
    public function generateAvatarPreview(): void
    {
        if (empty($this->name)) {
            return;
        }

        try {
            $diceBear = app(DiceBearService::class);
            
            $this->avatarPreview = $diceBear
                ->style($this->avatarStyle)
                ->seed($this->name)
                ->size(128)
                ->options([
                    'backgroundColor' => $this->getBackgroundColor(),
                    'mouth' => 'smile'
                ])
                ->getUrl();
        } catch (\Exception $e) {
            // Fallback to placeholder if service fails
            $this->avatarPreview = 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=128&background=random';
        }
    }

    /**
     * Change avatar style
     */
    public function changeAvatarStyle(string $style): void
    {
        $this->avatarStyle = $style;
        $this->generateAvatarPreview();
    }

    /**
     * Get random background color based on name
     */
    private function getBackgroundColor(): string
    {
        $colors = ['b6e3f4', 'c0aede', 'd1d4f9', 'ffd5dc', 'ffdfbf'];
        $index = abs(crc32($this->name)) % count($colors);
        return $colors[$index];
    }

    /**
     * Available avatar styles
     */
    public function getAvatarStyles(): array
    {
        return [
            'adventurer-neutral' => 'Adventurer',
            'bottts-neutral' => 'Bottts',
            'identicon' => 'Identicon',
            'initials' => 'Initials',
            'micah' => 'Micah',
            'personas' => 'Personas',
            'pixel-art-neutral' => 'Pixel Art',
            'rings' => 'Rings',
        ];
    }

    public function updatedName(): void
    {
        $this->generateAvatarPreview();
    }

    public function nextStep(): void
    {
        match ($this->step) {
            1 => $this->validate($this->stepOneRules()),
            2 => $this->validate($this->stepTwoRules()),
        };

        // Generate avatar when moving to step 2 (after name is validated)
        if ($this->step === 1) {
            $this->generateAvatarPreview();
        }

        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function register(AuthService $authService, DiceBearService $diceBear): void
    {
        $this->validate($this->stepThreeRules());

        // Generate and save avatar
        $avatarPath = null;
        try {
            $avatarPath = $diceBear
                ->style($this->avatarStyle)
                ->seed($this->name)
                ->size(256)
                ->options([
                    'backgroundColor' => $this->getBackgroundColor(),
                    'mouth' => 'smile',
                    'eyes' => 'happy'
                ])
                ->saveTo('avatars', time() . '_' . str_replace(' ', '_', $this->name) . '.svg', 'public');
        } catch (\Exception $e) {
            // Log error but continue registration
            \Log::error('Avatar generation failed: ' . $e->getMessage());
        }

        $user = $authService->registerAdmin([
            'name'                => $this->name,
            'email'               => $this->email,
            'phone'               => $this->phone,
            'password'            => $this->password,
            'license_number'      => $this->license_number,
            'specialty'           => $this->specialty,
            'years_of_experience' => $this->years_of_experience,
            'gender'              => $this->gender,
            'date_of_birth'       => $this->date_of_birth,
            'address'             => $this->address,
            'avatar'              => $avatarPath,
        ]);

        $this->redirect(route('subscription.select'), navigate: true);
    }

    public function render()
    {
        return view('livewire.auth.register-admin', [
            'avatarStyles' => $this->getAvatarStyles(),
        ]);
    }
}