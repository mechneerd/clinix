<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class Patients extends Component
{
    use WithPagination;

    public $pageTitle = 'Patient Management';
    public $search = '';
    
    // Form fields
    public $patientId = null;
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $date_of_birth = '';
    public $gender = 'male';
    public $blood_group = '';
    public $address = '';
    public $emergency_contact_name = '';
    public $emergency_contact_phone = '';
    public $allergies = '';
    public $medical_history = '';
    public $country_id = null;
    public $region_id = null;
    public $subregion_id = null;
    public $city_id = null;
    public $area_id = null;
    public $is_active = true;

    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    protected $listeners = [
        'phone-updated' => 'handlePhoneUpdate',
        'country-selected' => 'handleCountrySelect',
        'location-updated' => 'handleLocationUpdate'
    ];

    public function handlePhoneUpdate($phone) { $this->phone = $phone; }
    public function handleCountrySelect($id) { $this->country_id = $id; }
    
    public function handleLocationUpdate($level, $id)
    {
        $this->{$level . '_id'} = $id;
        
        // Reset children if parent changed (AddressSelector already handles its internal state, 
        // but we need to keep the parent component in sync)
        if ($level === 'country') {
            $this->region_id = $this->subregion_id = $this->city_id = $this->area_id = null;
        } elseif ($level === 'region') {
            $this->subregion_id = $this->city_id = $this->area_id = null;
        } elseif ($level === 'subregion') {
            $this->city_id = $this->area_id = null;
        } elseif ($level === 'city') {
            $this->area_id = null;
        }
    }

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . ($this->patientId ?? 'NULL'),
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string',
            'address' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'allergies' => 'nullable|string',
            'medical_history' => 'nullable|string',
            'country_id' => 'required|exists:countries,id',
            'region_id' => 'nullable|exists:regions,id',
            'subregion_id' => 'nullable|exists:subregions,id',
            'city_id' => 'nullable|exists:cities,id',
            'area_id' => 'nullable|exists:areas,id',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $patient = Patient::whereHas('clinics', fn($q) => $q->where('clinic_id', auth()->user()->clinic->id))
            ->findOrFail($id);
            
        $this->patientId = $patient->id;
        $this->first_name = $patient->first_name;
        $this->last_name = $patient->last_name;
        $this->email = $patient->email;
        $this->phone = $patient->phone;
        $this->date_of_birth = $patient->date_of_birth->format('Y-m-d');
        $this->gender = $patient->gender;
        $this->blood_group = $patient->blood_group;
        $this->address = $patient->address;
        $this->emergency_contact_name = $patient->emergency_contact_name;
        $this->emergency_contact_phone = $patient->emergency_contact_phone;
        $this->allergies = is_array($patient->allergies) ? implode(', ', $patient->allergies) : $patient->allergies;
        $this->medical_history = is_array($patient->medical_history) ? implode(', ', $patient->medical_history) : $patient->medical_history;
        $this->country_id = $patient->country_id;
        $this->region_id = $patient->region_id;
        $this->subregion_id = $patient->subregion_id;
        $this->city_id = $patient->city_id;
        $this->area_id = $patient->area_id;
        $this->is_active = $patient->is_active;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
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
            'allergies' => array_map('trim', explode(',', $this->allergies)),
            'medical_history' => array_map('trim', explode(',', $this->medical_history)),
            'country_id' => $this->country_id,
            'region_id' => $this->region_id,
            'subregion_id' => $this->subregion_id,
            'city_id' => $this->city_id,
            'area_id' => $this->area_id,
            'is_active' => $this->is_active,
        ];

        DB::transaction(function () use ($data) {
            if ($this->patientId) {
                Patient::findOrFail($this->patientId)->update($data);
                $this->dispatch('toast', ['type' => 'success', 'message' => 'Patient updated successfully']);
            } else {
                $patient = Patient::create($data);
                $patient->clinics()->attach(auth()->user()->clinic->id, [
                    'registered_at' => now(),
                    'registration_type' => 'walk-in'
                ]);
                $this->dispatch('toast', ['type' => 'success', 'message' => 'New patient registered successfully']);
            }
        });

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $patient = Patient::findOrFail($this->deleteId);
        // We only detach from this clinic
        $patient->clinics()->detach(auth()->user()->clinic->id);
        
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Patient removed from clinic records']);
        $this->showDeleteModal = false;
    }

    public function startChat($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $userId = $patient->user_id;

        if (!$userId) {
            $this->dispatch('toast', ['type' => 'error', 'message' => 'This patient does not have a user account.']);
            return;
        }

        $conversation = auth()->user()->conversations()
            ->whereHas('users', fn($q) => $q->where('users.id', $userId))
            ->first();

        if (!$conversation) {
            $conversation = \App\Models\Conversation::create([
                'clinic_id' => auth()->user()->clinic->id,
                'last_message_at' => now()
            ]);
            $conversation->users()->attach([auth()->id(), $userId]);
        }

        return redirect()->route('messages', ['conversationId' => $conversation->id]);
    }

    public function resetForm()
    {
        $this->patientId = null;
        $this->first_name = '';
        $this->last_name = '';
        $this->email = '';
        $this->phone = '';
        $this->date_of_birth = '';
        $this->gender = 'male';
        $this->blood_group = '';
        $this->address = '';
        $this->emergency_contact_name = '';
        $this->emergency_contact_phone = '';
        $this->allergies = '';
        $this->medical_history = '';
        $this->country_id = null;
        $this->region_id = null;
        $this->subregion_id = null;
        $this->city_id = null;
        $this->area_id = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $patients = Patient::whereHas('clinics', fn($q) => $q->where('clinic_id', auth()->user()->clinic->id))
            ->when($this->search, function($q) {
                $q->where(fn($query) => 
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('patient_code', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                );
            })
            ->latest()
            ->paginate(10);

        return view('livewire.clinic.patients', [
            'patients' => $patients
        ]);
    }
}
