<?php

namespace App\Livewire\Doctor;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Vital;
use App\Models\MedicalRecord;
use App\Models\Prescription;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class ClinicalEncounter extends Component
{
    public $appointment;
    public $patient;
    
    // Vitals
    public $vitals = [
        'blood_pressure' => '',
        'temperature' => '',
        'pulse' => '',
        'weight' => '',
        'height' => '',
        'oxygen_saturation' => ''
    ];

    // Medical Record
    public $diagnosis = '';
    public $symptoms = '';
    public $treatment_plan = '';
    public $notes = '';

    // Prescription Builder
    public $prescriptionItems = [];
    public $availableMedicines = [];
    public $searchMedicine = '';

    protected $rules = [
        'vitals.blood_pressure' => 'nullable|string',
        'vitals.temperature' => 'nullable|numeric',
        'vitals.pulse' => 'nullable|integer',
        'diagnosis' => 'required|string|min:3',
        'prescriptionItems.*.medicine_id' => 'required|exists:medicines,id',
        'prescriptionItems.*.medicine_batch_id' => 'nullable|exists:medicine_batches,id',
        'prescriptionItems.*.quantity' => 'required|integer|min:1',
    ];

    public function mount(Appointment $appointment)
    {
        $this->appointment = $appointment->load(['patient', 'vitals']);
        $this->patient = $appointment->patient;
        
        if ($this->appointment->vitals) {
            $this->vitals = [
                'blood_pressure' => $this->appointment->vitals->blood_pressure,
                'temperature' => $this->appointment->vitals->temperature,
                'pulse' => $this->appointment->vitals->pulse,
                'weight' => $this->appointment->vitals->weight,
                'height' => $this->appointment->vitals->height,
                'oxygen_saturation' => $this->appointment->vitals->oxygen_saturation,
            ];
        }

        $this->loadAvailableMedicines();
    }

    public function loadAvailableMedicines()
    {
        $this->availableMedicines = Medicine::where('clinic_id', $this->appointment->clinic_id)
            ->where('is_active', true)
            ->with(['batches' => fn($q) => $q->where('expiry_date', '>', now())->where('quantity', '>', 0)])
            ->get();
    }

    public function addPrescriptionItem($medicineId)
    {
        $medicine = Medicine::with(['batches' => fn($q) => $q->where('expiry_date', '>', now())->where('quantity', '>', 0)])->find($medicineId);
        if ($medicine) {
            $defaultBatchId = $medicine->batches->first()?->id;
            
            $this->prescriptionItems[] = [
                'medicine_id' => $medicine->id,
                'medicine_batch_id' => $defaultBatchId,
                'name' => $medicine->name,
                'dosage' => '',
                'frequency' => '',
                'duration' => '',
                'quantity' => 1,
                'batches' => $medicine->batches->toArray()
            ];
        }
        $this->searchMedicine = '';
    }

    public function removePrescriptionItem($index)
    {
        unset($this->prescriptionItems[$index]);
        $this->prescriptionItems = array_values($this->prescriptionItems);
    }

    public function saveEncounter()
    {
        $this->validate();

        DB::transaction(function () {
            // 1. Save Vitals
            Vital::updateOrCreate(
                ['appointment_id' => $this->appointment->id],
                array_merge($this->vitals, [
                    'patient_id' => $this->patient->id,
                    'recorded_by' => auth()->id()
                ])
            );

            // 2. Create Medical Record
            $record = MedicalRecord::create([
                'appointment_id' => $this->appointment->id,
                'patient_id' => $this->patient->id,
                'doctor_id' => auth()->user()->staff->id,
                'diagnosis' => $this->diagnosis,
                'symptoms' => $this->symptoms,
                'treatment_plan' => $this->treatment_plan,
                'notes' => $this->notes,
                'created_by' => auth()->id()
            ]);

            // 3. Save Prescription if items added
            if (!empty($this->prescriptionItems)) {
                $prescription = Prescription::create([
                    'medical_record_id' => $record->id,
                    'prescription_no' => 'PR-' . strtoupper(uniqid()),
                    'prescribed_date' => now(),
                    'created_by' => auth()->id()
                ]);

                foreach ($this->prescriptionItems as $item) {
                    $prescription->items()->create([
                        'medicine_id' => $item['medicine_id'],
                        'medicine_batch_id' => $item['medicine_batch_id'],
                        'dosage' => $item['dosage'],
                        'frequency' => $item['frequency'],
                        'duration' => $item['duration'],
                        'quantity' => $item['quantity'],
                    ]);

                    // Update Stock at Batch level if batch selected
                    if ($item['medicine_batch_id']) {
                        $batch = MedicineBatch::find($item['medicine_batch_id']);
                        if ($batch) {
                            $batch->decrement('quantity', $item['quantity']);
                        }
                    }

                    // Update Global Medicine Stock
                    $medicine = Medicine::find($item['medicine_id']);
                    if ($medicine) {
                        $medicine->decrement('stock_quantity', $item['quantity']);
                        
                        // Record Stock Movement
                        StockMovement::create([
                            'stockable_id' => $medicine->id,
                            'stockable_type' => Medicine::class,
                            'type' => 'dispense',
                            'quantity' => -$item['quantity'],
                            'reference_id' => 'PRESC-' . $prescription->id,
                            'created_by' => auth()->id(),
                            'notes' => 'Prescribed to ' . $this->patient->full_name . ($item['medicine_batch_id'] ? ' (Batch: ' . $item['medicine_batch_id'] . ')' : '')
                        ]);
                    }
                }
            }

            // 4. Finalize Appointment
            $this->appointment->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
        });

        session()->flash('success', 'Encounter finalized successfully.');
        return redirect()->route('doctor.dashboard');
    }

    public function render()
    {
        return view('livewire.doctor.clinical-encounter');
    }
}
