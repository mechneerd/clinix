<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Country;

class InternationalPhoneInput extends Component
{
    public $phone;
    public $country_id;
    public $selectedCountry;
    
    // Props passed from parent
    public $wire_model = 'phone';
    public $wire_country_model = 'country_id';
    public $label = 'Mobile Number';
    public $placeholder = 'Enter mobile number';

    public function mount($phone = null, $country_id = null)
    {
        $this->phone = $phone;
        $this->country_id = $country_id;
        
        if ($this->country_id) {
            $this->selectedCountry = Country::find($this->country_id);
        } else {
            // Default to first active country (e.g. India)
            $this->selectedCountry = Country::where('is_active', true)->first();
            if ($this->selectedCountry) {
                $this->country_id = $this->selectedCountry->id;
            }
        }
    }

    public function selectCountry($id)
    {
        $this->country_id = $id;
        $this->selectedCountry = Country::find($id);
        
        // Emit events to parent so it stays in sync
        $this->dispatch('country-selected', $this->country_id);
    }

    public function updatedPhone($value)
    {
        $this->dispatch('phone-updated', $this->phone);
    }

    public function render()
    {
        return view('livewire.components.international-phone-input', [
            'countries' => Country::where('is_active', true)->orderBy('name')->get()
        ]);
    }
}
