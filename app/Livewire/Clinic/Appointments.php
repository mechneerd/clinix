<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Staff;
use Carbon\Carbon;

class Appointments extends Component
{
    use WithPagination;

    public $pageTitle = 'Appointment Scheduling';
    public $search = '';
    public $filterDate = '';
    
    // Form fields
    public $appointmentId = null;
    public $patient_id = '';
    public $doctor_id = '';
    public $appointment_date = '';
    public $start_time = '';
    public $end_time = '';
    public $type = 'consultation';
    public $status = 'scheduled';
    public $chief_complaint = '';
    public $notes = '';
    public $fee = '';

    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    protected $rules = [
        'patient_id' => 'required|exists:patients,id',
        'doctor_id' => 'required|exists:staff,id',
        'appointment_date' => 'required|date|after_or_equal:today',
        'start_time' => 'required',
        'end_time' => 'required|after:start_time',
        'type' => 'required|string',
        'status' => 'required|string',
        'chief_complaint' => 'required|string',
        'notes' => 'nullable|string',
        'fee' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->filterDate = today()->format('Y-m-d');
        $this->appointment_date = today()->format('Y-m-d');
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $appointment = Appointment::where('clinic_id', auth()->user()->clinic->id)->findOrFail($id);
        
        $this->appointmentId = $appointment->id;
        $this->patient_id = $appointment->patient_id;
        $this->doctor_id = $appointment->doctor_id;
        $this->appointment_date = $appointment->appointment_date->format('Y-m-d');
        $this->start_time = $appointment->start_time->format('H:i');
        $this->end_time = $appointment->end_time->format('H:i');
        $this->type = $appointment->type;
        $this->status = $appointment->status;
        $this->chief_complaint = $appointment->chief_complaint;
        $this->notes = $appointment->notes;
        $this->fee = $appointment->fee;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $start = Carbon::parse($this->appointment_date . ' ' . $this->start_time);
        $end = Carbon::parse($this->appointment_date . ' ' . $this->end_time);

        $data = [
            'clinic_id' => auth()->user()->clinic->id,
            'patient_id' => $this->patient_id,
            'doctor_id' => $this->doctor_id,
            'appointment_date' => $this->appointment_date,
            'start_time' => $start,
            'end_time' => $end,
            'type' => $this->type,
            'status' => $this->status,
            'chief_complaint' => $this->chief_complaint,
            'notes' => $this->notes,
            'fee' => $this->fee,
        ];

        if ($this->appointmentId) {
            Appointment::where('clinic_id', auth()->user()->clinic->id)->findOrFail($this->appointmentId)->update($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Appointment updated successfully']);
        } else {
            Appointment::create($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Appointment scheduled successfully']);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function checkIn($id)
    {
        $appointment = Appointment::where('clinic_id', auth()->user()->clinic->id)->findOrFail($id);
        $appointment->update([
            'status' => 'checked_in',
            'checked_in_at' => now()
        ]);
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Patient checked in']);
    }

    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        Appointment::where('clinic_id', auth()->user()->clinic->id)->findOrFail($this->deleteId)->delete();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Appointment cancelled']);
        $this->showDeleteModal = false;
    }

    public function resetForm()
    {
        $this->appointmentId = null;
        $this->patient_id = '';
        $this->doctor_id = '';
        $this->appointment_date = today()->format('Y-m-d');
        $this->start_time = '';
        $this->end_time = '';
        $this->type = 'consultation';
        $this->status = 'scheduled';
        $this->chief_complaint = '';
        $this->notes = '';
        $this->fee = '';
        $this->resetValidation();
    }

    public function render()
    {
        $clinicId = auth()->user()->clinic->id;
        
        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->where('clinic_id', $clinicId)
            ->when($this->filterDate, fn($q) => $q->whereDate('appointment_date', $this->filterDate))
            ->when($this->search, function($q) {
                $q->whereHas('patient', function($query) {
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                          ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->paginate(10);

        return view('livewire.clinic.appointments', [
            'appointments' => $appointments,
            'patients' => Patient::whereHas('clinics', fn($q) => $q->where('clinic_id', $clinicId))->get(),
            'doctors' => Staff::where('clinic_id', $clinicId)->where('role', 'doctor')->with('user')->get(),
        ]);
    }
}
