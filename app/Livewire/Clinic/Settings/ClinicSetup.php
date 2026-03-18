<?php

namespace App\Livewire\Clinic\Settings;

use Livewire\Component;
use App\Models\Clinic;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ClinicSetup extends Component
{
    use WithFileUploads;

    public $pageTitle = 'Clinic Profile';
    public $clinicId;
    public $name;
    public $description;
    public $email;
    public $phone;
    public $address;
    public $city;
    public $state;
    public $country;
    public $logo;
    public $existingLogo;

    public function mount()
    {
        $clinic = auth()->user()->clinic;
        if ($clinic) {
            $this->clinicId = $clinic->id;
            $this->name = $clinic->name;
            $this->description = $clinic->description;
            $this->email = $clinic->email;
            $this->phone = $clinic->phone;
            $this->address = $clinic->address;
            $this->city = $clinic->city;
            $this->state = $clinic->state;
            $this->country = $clinic->country;
            $this->existingLogo = $clinic->logo;
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'logo' => 'nullable|image|max:2048', // 2MB Max
        ];
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => $this->clinicId ? auth()->user()->clinic->slug : Str::slug($this->name) . '-' . rand(1000, 9999),
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
        ];

        if ($this->logo) {
            $data['logo'] = $this->logo->store('clinics/logos', 'public');
        }

        if ($this->clinicId) {
            Clinic::find($this->clinicId)->update($data);
        } else {
            $data['user_id'] = auth()->id();
            $data['status'] = 'active';
            Clinic::create($data);
        }

        $this->dispatch('toast', ['type' => 'success', 'message' => 'Clinic profile updated successfully!']);
    }

    public function render()
    {
        return view('livewire.clinic.settings.clinic-setup');
    }
}
