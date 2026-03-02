<?php

namespace App\Livewire\Admin\Staff;

use App\Models\Clinic;
use App\Models\Department;
use App\Services\StaffService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Add Staff — Clinix')]
class Add extends Component
{
    public Clinic $clinic;

    // Basic info
    public string $name              = '';
    public string $email             = '';
    public string $phone             = '';
    public string $role              = 'nurse';
    public string $gender            = '';
    public string $date_of_birth     = '';

    // Professional
    public string  $qualification       = '';
    public string  $specializations     = '';
    public int     $experience_years    = 0;
    public string  $license_number      = '';
    public string  $license_expiry      = '';
    public ?int    $department_id       = null;
    public string  $employment_type     = 'full_time';
    public string  $joining_date        = '';
    public ?float  $consultation_fee    = null;
    public string  $biography           = '';
    public bool    $is_available_for_online = false;

    // Doctor schedule
    public bool  $addSchedule = false;
    public array $schedules   = [
        ['day' => 'monday',    'is_available' => false, 'start_time' => '09:00', 'end_time' => '17:00', 'slot_duration' => 30, 'max_patients' => 10],
        ['day' => 'tuesday',   'is_available' => false, 'start_time' => '09:00', 'end_time' => '17:00', 'slot_duration' => 30, 'max_patients' => 10],
        ['day' => 'wednesday', 'is_available' => false, 'start_time' => '09:00', 'end_time' => '17:00', 'slot_duration' => 30, 'max_patients' => 10],
        ['day' => 'thursday',  'is_available' => false, 'start_time' => '09:00', 'end_time' => '17:00', 'slot_duration' => 30, 'max_patients' => 10],
        ['day' => 'friday',    'is_available' => false, 'start_time' => '09:00', 'end_time' => '17:00', 'slot_duration' => 30, 'max_patients' => 10],
        ['day' => 'saturday',  'is_available' => false, 'start_time' => '09:00', 'end_time' => '14:00', 'slot_duration' => 30, 'max_patients' => 8],
        ['day' => 'sunday',    'is_available' => false, 'start_time' => '09:00', 'end_time' => '14:00', 'slot_duration' => 30, 'max_patients' => 8],
    ];

    public function mount(int $clinicId): void
    {
        $this->clinic       = Clinic::where('id', $clinicId)->where('owner_id', auth()->id())->firstOrFail();
        $this->joining_date = today()->format('Y-m-d');
    }

    public function updatedRole(): void
    {
        $this->addSchedule = ($this->role === 'doctor');
    }

    protected function rules(): array
    {
        return [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email',
            'phone'           => 'nullable|string|max:20',
            'role'            => 'required|string',
            'gender'          => 'nullable|string',
            'date_of_birth'   => 'nullable|date|before:today',
            'qualification'   => 'nullable|string|max:255',
            'experience_years'=> 'nullable|integer|min:0',
            'license_number'  => 'nullable|string|max:100',
            'department_id'   => 'nullable|integer|exists:departments,id',
            'employment_type' => 'required|string',
            'joining_date'    => 'required|date',
            'consultation_fee'=> 'nullable|numeric|min:0',
        ];
    }

    public function save(StaffService $service): void
    {
        $this->validate();

        $user = $service->addStaff($this->clinic, [
            'name'                   => $this->name,
            'email'                  => $this->email,
            'phone'                  => $this->phone,
            'role'                   => $this->role,
            'gender'                 => $this->gender,
            'date_of_birth'          => $this->date_of_birth ?: null,
            'qualification'          => $this->qualification,
            'specializations'        => $this->specializations,
            'experience_years'       => $this->experience_years,
            'license_number'         => $this->license_number,
            'license_expiry'         => $this->license_expiry ?: null,
            'department_id'          => $this->department_id,
            'employment_type'        => $this->employment_type,
            'joining_date'           => $this->joining_date,
            'consultation_fee'       => $this->consultation_fee,
            'biography'              => $this->biography,
            'is_available_for_online'=> $this->is_available_for_online,
        ]);

        // Save doctor schedule
        if ($this->role === 'doctor' && $this->addSchedule) {
            $service->saveDoctorSchedule($user->id, $this->clinic->id, $this->schedules);
        }

        $this->redirect(route('admin.staff.index', $this->clinic->id), navigate: true);
    }

    public function render()
    {
        $departments = Department::where('clinic_id', $this->clinic->id)->where('is_active', true)->get();
        $roles = ['doctor','nurse','lab_technician','pharmacist','manager','receptionist'];

        return view('livewire.admin.staff.add', compact('departments', 'roles'));
    }
}
