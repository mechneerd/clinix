<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use App\Models\Room;
use App\Models\Ward;
use App\Models\Bed;
use App\Models\PatientAdmission;
use App\Models\Patient;
use Livewire\WithPagination;

class Admissions extends Component
{
    use WithPagination;

    public $activeTab = 'grid';
    public $showAdmissionModal = false;
    
    public $ward_id = '';
    public $patient_id;
    public $room_id;
    public $bed_id;
    public $reason;
    public $admitted_at;

    protected $listeners = ['refreshAdmissions' => '$refresh'];

    public function mount()
    {
        $this->admitted_at = now()->format('Y-m-d\TH:i');
    }

    public function openAdmissionModal($roomId = null)
    {
        $this->room_id = $roomId;
        $this->showAdmissionModal = true;
    }

    public function updatedWardId()
    {
        $this->resetPage();
    }

    public function admitPatient()
    {
        $this->validate([
            'patient_id' => 'required|exists:patients,id',
            'room_id' => 'required|exists:rooms,id',
            'bed_id' => 'nullable|exists:beds,id',
            'reason' => 'required|string',
        ]);

        PatientAdmission::create([
            'patient_id' => $this->patient_id,
            'room_id' => $this->room_id,
            'bed_id' => $this->bed_id,
            'admitted_at' => $this->admitted_at,
            'reason' => $this->reason,
            'admitted_by' => auth()->id(),
            'status' => 'admitted'
        ]);

        if ($this->bed_id) {
            Bed::find($this->bed_id)->update(['status' => 'occupied']);
        }

        Room::find($this->room_id)->update(['is_occupied' => true]);

        $this->reset(['patient_id', 'room_id', 'bed_id', 'reason', 'showAdmissionModal']);
        session()->flash('success', 'Patient admitted successfully.');
    }

    public function dischargePatient($admissionId)
    {
        $admission = PatientAdmission::findOrFail($admissionId);
        $admission->update([
            'discharged_at' => now(),
            'status' => 'discharged'
        ]);

        if ($admission->bed_id) {
            Bed::find($admission->bed_id)->update(['status' => 'available']);
        }

        $otherOccupied = PatientAdmission::where('room_id', $admission->room_id)
            ->where('status', 'admitted')
            ->exists();

        if (!$otherOccupied) {
            $admission->room->update(['is_occupied' => false]);
        }
        
        session()->flash('success', 'Patient discharged successfully.');
    }

    public function render()
    {
        $clinicId = auth()->user()->staff->clinic_id;
        
        $roomsQuery = Room::where('clinic_id', $clinicId);
        if ($this->ward_id) {
            $roomsQuery->where('ward_id', $this->ward_id);
        }
        
        // Load active admissions directly into rooms to avoid @php in blade
        $rooms = $roomsQuery->with(['beds', 'ward', 'activeAdmissions.patient', 'activeAdmissions.bed'])->get();

        $admissions = PatientAdmission::with(['patient', 'room', 'bed', 'admittedBy'])
            ->whereHas('room', fn($q) => $q->where('clinic_id', $clinicId))
            ->latest()
            ->paginate(10);

        return view('livewire.clinic.admissions', [
            'rooms' => $rooms,
            'wards' => Ward::where('clinic_id', $clinicId)->get(),
            'admissions' => $admissions,
            'availablePatients' => Patient::whereHas('clinics', fn($q) => $q->where('clinics.id', $clinicId))->get(),
            'availableBeds' => $this->room_id ? Bed::where('room_id', $this->room_id)->where('status', 'available')->get() : [],
        ]);
    }
}
