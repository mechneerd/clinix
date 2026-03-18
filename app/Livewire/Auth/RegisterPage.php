<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterPage extends Component
{
    public $registerType = 'clinic'; // clinic, patient
    
    // Common fields
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    public $password_confirmation = '';
    
    // Clinic specific
    public $clinic_name = '';
    public $clinic_phone = '';
    public $clinic_address = '';
    public $clinic_city = '';
    public $clinic_state = '';
    public $clinic_country = '';
    public $selected_package_id = '';
    
    // Patient specific
    public $first_name = '';
    public $last_name = '';
    public $date_of_birth = '';
    public $gender = '';
    public $blood_group = '';
    public $address = '';
    public $emergency_contact_name = '';
    public $emergency_contact_phone = '';

    public function mount()
    {
        $this->selected_package_id = Package::where('is_active', true)->first()?->id ?? '';
    }

    protected function rules()
    {
        $common = [
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|min:8|confirmed',
        ];

        if ($this->registerType === 'clinic') {
            return array_merge($common, [
                'name' => 'required|string|max:255',
                'clinic_name' => 'required|string|max:255',
                'clinic_phone' => 'required|string|max:20',
                'clinic_address' => 'required|string',
                'clinic_city' => 'required|string',
                'clinic_state' => 'required|string',
                'clinic_country' => 'required|string',
                'selected_package_id' => 'required|exists:packages,id',
            ]);
        }

        return array_merge($common, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
        ]);
    }

    public function register()
    {
        $this->validate();

        $user = DB::transaction(function () {
            // Create user
            $userData = [
                'name' => $this->registerType === 'clinic' ? $this->name : $this->first_name . ' ' . $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'password' => Hash::make($this->password),
                'user_type' => $this->registerType === 'clinic' ? 'clinic_admin' : 'patient',
            ];

            $user = User::create($userData);

            if ($this->registerType === 'clinic') {
                $this->createClinic($user);
                $user->assignRole('clinic-admin');
            } else {
                $this->createPatient($user);
                $user->assignRole('patient');
            }

            return $user;
        });

        Auth::login($user);

        $user->generateOtp();
        $user->notify(new \App\Notifications\OtpNotification($user->otp));

        return redirect()->route('verify-otp');
    }

    protected function createClinic(User $user)
    {
        $package = Package::find($this->selected_package_id);

        Clinic::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'name' => $this->clinic_name,
            'slug' => Str::slug($this->clinic_name) . '-' . uniqid(),
            'email' => $this->email,
            'phone' => $this->clinic_phone,
            'address' => $this->clinic_address,
            'city' => $this->clinic_city,
            'state' => $this->clinic_state,
            'country' => $this->clinic_country,
            'package_expires_at' => now()->addDays($package->duration_days),
            'status' => 'active',
        ]);
    }

    protected function createPatient(User $user)
    {
        Patient::create([
            'user_id' => $user->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'blood_group' => $this->blood_group,
            'address' => $this->address,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
        ]);
    }

    public function render()
    {
        return view('livewire.auth.register-page', [
            'packages' => Package::where('is_active', true)->get(),
        ])->layout('components.layouts.guest');
    }
}