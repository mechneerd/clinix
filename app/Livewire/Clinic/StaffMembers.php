<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Staff;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class StaffMembers extends Component
{
    use WithPagination;

    public $pageTitle = 'Staff Management';
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
    public $role = '';
    public $qualification = '';
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
            'role' => 'required|string|in:nurse,receptionist,lab_worker,pharmacy_worker,lab_manager,pharmacy_manager,reception_manager',
            'qualification' => 'nullable|string',
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
        $staff = Staff::where('clinic_id', auth()->user()->clinic->id)->where('role', '!=', 'doctor')->findOrFail($id);
        $this->staffId = $staff->id;
        $this->name = $staff->user->name;
        $this->email = $staff->user->email;
        $this->phone = $staff->user->phone;
        $this->department_id = $staff->department_id;
        $this->employee_id = $staff->employee_id;
        $this->qualification = $staff->qualification;
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

                // If role changed, re-assign spatie roles if needed
                if ($staff->role !== $this->role) {
                    $user->syncRoles([$this->role]);
                }

                $staff->update([
                    'department_id' => $this->department_id,
                    'employee_id' => $this->employee_id,
                    'role' => $this->role,
                    'qualification' => $this->qualification,
                    'is_active' => $this->is_active,
                ]);

                $this->dispatch('toast', ['type' => 'success', 'message' => 'Staff profile updated successfully']);
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

                $user->assignRole($this->role);

                Staff::create([
                    'user_id' => $user->id,
                    'clinic_id' => auth()->user()->clinic->id,
                    'department_id' => $this->department_id,
                    'employee_id' => $this->employee_id,
                    'role' => $this->role,
                    'qualification' => $this->qualification,
                    'joining_date' => now(),
                    'is_active' => true,
                ]);

                $this->dispatch('toast', ['type' => 'success', 'message' => 'New staff member added successfully']);
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
        $staff = Staff::where('clinic_id', auth()->user()->clinic->id)->where('role', '!=', 'doctor')->findOrFail($this->deleteId);
        
        DB::transaction(function () use ($staff) {
            $user = $staff->user;
            $staff->delete();
            $user->update(['is_active' => false]);
        });

        $this->dispatch('toast', ['type' => 'success', 'message' => 'Staff member removed successfully']);
        $this->showDeleteModal = false;
    }

    public function startChat($staffId)
    {
        $staff = Staff::findOrFail($staffId);
        $userId = $staff->user_id;

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
        $this->staffId = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->country_id = null;
        $this->password = '';
        $this->department_id = '';
        $this->employee_id = '';
        $this->role = '';
        $this->qualification = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        $staffMembers = Staff::with(['user', 'department'])
            ->where('clinic_id', auth()->user()->clinic->id)
            ->where('role', '!=', 'doctor')
            ->when($this->search, function($q) {
                $q->whereHas('user', fn($user) => $user->where('name', 'like', '%' . $this->search . '%'))
                  ->orWhere('employee_id', 'like', '%' . $this->search . '%')
                  ->orWhere('role', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        $departments = Department::where('clinic_id', auth()->user()->clinic->id)->get();

        return view('livewire.clinic.staff-members', [
            'staffMembers' => $staffMembers,
            'departments' => $departments
        ]);
    }
}
