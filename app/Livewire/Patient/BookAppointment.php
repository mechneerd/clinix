<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Models\Clinic;
use App\Models\Department;
use App\Models\Staff;
use App\Models\Appointment;
use Carbon\Carbon;

class BookAppointment extends Component
{
    public $clinic;
    public $departments = [];
    public $doctors = [];
    
    public $selectedDepartment = null;
    public $selectedDoctor = null;
    public $selectedDate = null;
    public $selectedTime = null;
    public $chiefComplaint = '';
    
    public $availableTimes = [];

    public function mount($clinic_slug = null)
    {
        if ($clinic_slug) {
            $this->clinic = Clinic::where('slug', $clinic_slug)->firstOrFail();
            $this->loadDepartments();
        } else {
            // If no clinic selected, redirect to browse
            return redirect()->route('patient.browse-clinics');
        }
        $this->selectedDate = date('Y-m-d');
    }

    public function loadDepartments()
    {
        $this->departments = $this->clinic->departments()->where('is_active', true)->get();
        $this->doctors = $this->clinic->staff()->where('role', 'doctor')->where('is_active', true)->get();
    }

    public function updatedSelectedDepartment($value)
    {
        $this->selectedDoctor = null;
        if ($value) {
            $this->doctors = $this->clinic->staff()
                ->where('role', 'doctor')
                ->where('department_id', $value)
                ->where('is_active', true)
                ->get();
        } else {
            $this->doctors = $this->clinic->staff()
                ->where('role', 'doctor')
                ->where('is_active', true)
                ->get();
        }
    }

    public function updatedSelectedDate($value)
    {
        $this->generateTimeSlots();
    }

    public function updatedSelectedDoctor($value)
    {
        $this->generateTimeSlots();
    }

    public function generateTimeSlots()
    {
        if (!$this->selectedDoctor || !$this->selectedDate) {
            $this->availableTimes = [];
            return;
        }

        // Simplification for now: 9 AM to 5 PM, 30 min intervals
        $slots = [];
        $start = Carbon::parse('09:00');
        $end = Carbon::parse('17:00');
        
        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(30);
        }

        // Filter out already booked slots
        $booked = Appointment::where('doctor_id', $this->selectedDoctor)
            ->whereDate('appointment_date', $this->selectedDate)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function($a) {
                return Carbon::parse($a->start_time)->format('H:i');
            })
            ->toArray();

        $this->availableTimes = array_diff($slots, $booked);
    }

    public function book()
    {
        $this->validate([
            'selectedDoctor' => 'required',
            'selectedDate' => 'required|date|after_or_equal:today',
            'selectedTime' => 'required',
            'chiefComplaint' => 'required|min:5',
        ]);

        $patient = auth()->user()->patient;
        if (!$patient) {
            session()->flash('error', 'Patient profile not found.');
            return;
        }

        $doctor = Staff::findOrFail($this->selectedDoctor);
        
        $startTime = Carbon::parse($this->selectedDate . ' ' . $this->selectedTime);
        $endTime = (clone $startTime)->addMinutes(30);

        $appointment = Appointment::create([
            'clinic_id' => $this->clinic->id,
            'patient_id' => $patient->id,
            'doctor_id' => $this->selectedDoctor,
            'appointment_date' => $this->selectedDate,
            'start_time' => $startTime->toTimeString(),
            'end_time' => $endTime->toTimeString(),
            'status' => Appointment::STATUS_PENDING,
            'type' => 'online', // Booking via portal
            'chief_complaint' => $this->chiefComplaint,
            'fee' => $doctor->consultation_fee,
        ]);

        // Ensure patient is attached to clinic
        $isAttached = $this->clinic->patients()->where('patient_id', $patient->id)->exists();
        if (!$isAttached) {
            $this->clinic->patients()->attach($patient->id, [
                'registered_at' => now(),
                'registration_type' => 'online'
            ]);
        }

        session()->flash('success', 'Your appointment request for ' . $this->clinic->name . ' has been submitted and is awaiting confirmation.');
        return redirect()->route('patient.dashboard');
    }

    public function render()
    {
        return view('livewire.patient.book-appointment');
    }
}
