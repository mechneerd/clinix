<?php

namespace App\Livewire\Patient;

use App\Models\Clinic;
use App\Models\Department;
use App\Services\AppointmentService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.patient')]
#[Title('Book Appointment — Clinix')]
class BookAppointment extends Component
{
    public int    $step         = 1;
    public ?int   $clinicId     = null;
    public ?int   $departmentId = null;
    public ?int   $doctorId     = null;
    public string $date         = '';
    public string $selectedSlot = '';
    public string $type         = 'in_person';
    public string $symptoms     = '';
    public string $notes        = '';

    public array $availableSlots = [];

    public function updatedClinicId(): void
    {
        $this->departmentId  = null;
        $this->doctorId      = null;
        $this->availableSlots = [];
        $this->selectedSlot  = '';
    }

    public function updatedDoctorId(): void
    {
        $this->availableSlots = [];
        $this->selectedSlot   = '';
        $this->loadSlots();
    }

    public function updatedDate(): void
    {
        $this->availableSlots = [];
        $this->selectedSlot   = '';
        $this->loadSlots();
    }

    public function loadSlots(): void
    {
        if (!$this->doctorId || !$this->clinicId || !$this->date) return;

        $this->availableSlots = app(AppointmentService::class)
            ->getAvailableSlots($this->doctorId, $this->clinicId, $this->date);
    }

    public function nextStep(): void
    {
        match ($this->step) {
            1 => $this->validate([
                'clinicId'  => 'required|integer',
                'doctorId'  => 'required|integer',
                'date'      => 'required|date|after_or_equal:today',
                'selectedSlot' => 'required',
            ]),
            2 => $this->validate([
                'type'     => 'required',
                'symptoms' => 'nullable|max:1000',
            ]),
        };
        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step = max(1, $this->step - 1);
    }

    public function confirmBooking(AppointmentService $service): void
    {
        $this->validate([
            'clinicId'     => 'required',
            'doctorId'     => 'required',
            'date'         => 'required|date',
            'selectedSlot' => 'required',
        ]);

        // Parse slot end time (add 30 min)
        $endTime = \Carbon\Carbon::parse($this->selectedSlot)->addMinutes(30)->format('H:i:s');

        $appointment = $service->bookAppointment([
            'clinic_id'    => $this->clinicId,
            'doctor_id'    => $this->doctorId,
            'patient_id'   => auth()->id(),
            'department_id'=> $this->departmentId,
            'appointment_date' => $this->date,
            'start_time'   => $this->selectedSlot,
            'end_time'     => $endTime,
            'type'         => $this->type,
            'symptoms'     => $this->symptoms,
            'notes'        => $this->notes,
            'status'       => 'pending',
        ]);

        $this->redirect(route('patient.appointments'), navigate: true);
    }

    public function render(AppointmentService $service)
    {
        $clinics = Clinic::where('status', 'active')->get();
        $departments = $this->clinicId
            ? Department::where('clinic_id', $this->clinicId)->where('is_active', true)->get()
            : collect();
        $doctors = $this->clinicId
            ? $service->getAvailableDoctors($this->clinicId)
            : collect();

        return view('livewire.patient.book-appointment', compact('clinics', 'departments', 'doctors'));
    }
}