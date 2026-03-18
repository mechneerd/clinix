<?php

namespace App\Livewire\Patient;

use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CompleteProfile extends Component
{
    public $first_name = '';
    public $last_name = '';
    public $phone = '';
    public $date_of_birth = '';
    public $gender = '';
    public $blood_group = '';
    public $address = '';
    public $emergency_contact_name = '';
    public $emergency_contact_phone = '';

    public function mount()
    {
        $user = Auth::user();
        if (!$user || $user->patient) {
            return redirect()->route('patient.dashboard');
        }

        // Pre-fill name from Google
        $nameParts = explode(' ', $user->name, 2);
        $this->first_name = $nameParts[0] ?? '';
        $this->last_name = $nameParts[1] ?? '';
    }

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
        ];
    }

    public function save()
    {
        $this->validate();

        Patient::create([
            'user_id' => Auth::id(),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => Auth::user()->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'blood_group' => $this->blood_group,
            'address' => $this->address,
            'emergency_contact_name' => $this->emergency_contact_name,
            'emergency_contact_phone' => $this->emergency_contact_phone,
            'is_active' => true,
        ]);

        return redirect()->route('patient.dashboard');
    }

    public function render()
    {
        return view('livewire.patient.complete-profile')
            ->layout('components.layouts.guest');
    }
}
