<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Staff;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class Doctors extends Component
{
    use WithPagination;

    public $pageTitle = 'Doctor Management';
    public $search = '';
    
    // User fields
    public $name = '';
    public $email = '';
    public $phone = '';
    public $password = '';
    
    // Staff fields
    public $staffId = null;
    public $department_id = '';
    public $employee_id = '';
    public $qualification = '';
    public $license_number = '';
    public $consultation_fee = '';
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->staffId ? Staff::find($this->staffId)->user_id : 'NULL'),
            'phone' => 'required|string|max:20',
            'password' => $this->staffId ? 'nullable|min:8' : 'required|min:8',
            'department_id' => 'required|exists:departments,id',
            'employee_id' => 'required|string|unique:staff,employee_id,' . ($this->staffId ?? 'NULL'),
            'qualification' => 'required|string',
            'license_number' => 'required|string',
            'consultation_fee' => 'required|numeric|min:0',
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
        $staff = Staff::where('clinic_id', auth()->user()->clinic->id)->where('role', 'doctor')->findOrFail($id);
        $this->staffId = $staff->id;
        $this->name = $staff->user->name;
        $this->email = $staff->user->email;
        $this->phone = $staff->user->phone;
        $this->department_id = $staff->department_id;
        $this->employee_id = $staff->employee_id;
        $this->qualification = $staff->qualification;
        $this->license_number = $staff->license_number;
        $this->consultation_fee = $staff->consultation_fee;
        $this->country_id = $staff->user->country_id;
        $this->region_id = $staff->user->region_id;
        $this->subregion_id = $staff->user->subregion_id;
        $this->city_id = $staff->user->city_id;
        $this->area_id = $staff->user->area_id;
        $this->is_active = $staff->is_active;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function () {
            if ($this->staffId) {
                $staff = Staff::findOrFail($this->staffId);
                $user = $staff->user;
                
                $user->update([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'country_id' => $this->country_id,
                ]);

                if ($this->password) {
                    $user->update(['password' => Hash::make($this->password)]);
                }

                $staff->update([
                    'department_id' => $this->department_id,
                    'employee_id' => $this->employee_id,
                    'qualification' => $this->qualification,
                    'license_number' => $this->license_number,
                    'consultation_fee' => $this->consultation_fee,
                    'is_active' => $this->is_active,
                ]);

                $this->dispatch('toast', ['type' => 'success', 'message' => 'Doctor profile updated successfully']);
            } else {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone' => $this->phone,
                    'country_id' => $this->country_id,
                    'region_id' => $this->region_id,
                    'subregion_id' => $this->subregion_id,
                    'city_id' => $this->city_id,
                    'area_id' => $this->area_id,
                    'password' => Hash::make($this->password),
                    'user_type' => 'staff',
                    'is_active' => true,
                ]);

                $user->assignRole('doctor');

                Staff::create([
                    'user_id' => $user->id,
                    'clinic_id' => auth()->user()->clinic->id,
                    'department_id' => $this->department_id,
                    'employee_id' => $this->employee_id,
                    'role' => 'doctor',
                    'qualification' => $this->qualification,
                    'license_number' => $this->license_number,
                    'joining_date' => now(),
                    'consultation_fee' => $this->consultation_fee,
                    'is_active' => true,
                ]);

                $this->dispatch('toast', ['type' => 'success', 'message' => 'New doctor added successfully']);
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
        $staff = Staff::where('clinic_id', auth()->user()->clinic->id)->where('role', 'doctor')->findOrFail($this->deleteId);
        
        // Check for active appointments
        if ($staff->appointments()->where('status', 'scheduled')->count() > 0) {
            $this->dispatch('toast', ['type' => 'error', 'message' => 'Cannot delete doctor with scheduled appointments']);
            $this->showDeleteModal = false;
            return;
        }

        DB::transaction(function () use ($staff) {
            $user = $staff->user;
            $staff->delete();
            // Don't delete user record, just mark inactive or leave as is if they have other roles
            $user->update(['is_active' => false]);
        });

        $this->dispatch('toast', ['type' => 'success', 'message' => 'Doctor removed successfully']);
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->staffId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->country_id = null;
        $this->password = '';
        $this->department_id = '';
        $this->employee_id = '';
        $this->qualification = '';
        $this->license_number = '';
        $this->consultation_fee = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $doctors = Staff::with(['user', 'department'])
            ->where('clinic_id', auth()->user()->clinic->id)
            ->where('role', 'doctor')
            ->when($this->search, function($q) {
                $q->whereHas('user', fn($user) => $user->where('name', 'like', '%' . $this->search . '%'))
                  ->orWhere('employee_id', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $departments = Department::where('clinic_id', auth()->user()->clinic->id)->get();

        return view('livewire.clinic.doctors', [
            'doctors' => $doctors,
            'departments' => $departments
        ]);
    }
}
