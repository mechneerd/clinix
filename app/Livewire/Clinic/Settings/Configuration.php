<?php

namespace App\Livewire\Clinic\Settings;

use Livewire\Component;
use App\Models\ClinicSetting;
use Illuminate\Support\Facades\Cache;

class Configuration extends Component
{
    public $settings = [];
    public $newKey = '';
    public $newValue = '';

    public function mount()
    {
        $this->loadSettings();
    }

    public function loadSettings()
    {
        $clinicId = auth()->user()->staff->clinic_id;
        $this->settings = ClinicSetting::where('clinic_id', $clinicId)->get()->toArray();
        
        // Ensure defaults exist if empty
        if (empty($this->settings)) {
            $defaults = [
                ['key' => 'currency', 'value' => 'USD', 'type' => 'string'],
                ['key' => 'timezone', 'value' => 'UTC', 'type' => 'string'],
                ['key' => 'appointment_duration', 'value' => '30', 'type' => 'integer'],
                ['key' => 'sms_enabled', 'value' => 'false', 'type' => 'boolean'],
            ];
            
            foreach ($defaults as $def) {
                ClinicSetting::create(array_merge($def, ['clinic_id' => $clinicId]));
            }
            $this->settings = ClinicSetting::where('clinic_id', $clinicId)->get()->toArray();
        }
    }

    public function updateSetting($id, $value)
    {
        ClinicSetting::where('id', $id)->update(['value' => $value]);
        $this->loadSettings();
        session()->flash('success', 'Setting updated.');
    }

    public function addSetting()
    {
        $this->validate([
            'newKey' => 'required|string|alpha_dash',
            'newValue' => 'required',
        ]);

        ClinicSetting::create([
            'clinic_id' => auth()->user()->staff->clinic_id,
            'key' => $this->newKey,
            'value' => $this->newValue,
            'type' => 'string'
        ]);

        $this->reset(['newKey', 'newValue']);
        $this->loadSettings();
        session()->flash('success', 'New setting added.');
    }

    public function deleteSetting($id)
    {
        ClinicSetting::destroy($id);
        $this->loadSettings();
    }

    public function render()
    {
        return view('livewire.clinic.settings.configuration');
    }
}
