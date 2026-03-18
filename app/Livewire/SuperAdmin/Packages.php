<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Package;
use App\Models\Clinic; // Added by user instruction
use App\Models\User;   // Added by user instruction
use App\Models\Staff;  // Added by user instruction
use Illuminate\Support\Facades\DB;

class Packages extends Component
{
    use WithPagination;

    public $pageTitle = 'Manage Packages';
    
    // Form fields
    public $packageId = null;
    public $name = '';
    public $description = '';
    public $price = '';
    public $billing_cycle = 'monthly';
    public $duration_days = 30;
    public $max_clinics = 1;
    public $max_labs = 0;
    public $max_doctors = 1;
    public $max_staff = 5;
    public $max_patients_per_month = 100;
    public $storage_limit_mb = 1024;
    public $api_access = false;
    public $white_label = false;
    public $advanced_reporting = false;
    public $sms_notifications = false;
    public $telemedicine = false;
    public $is_active = true;
    public $is_approved = false;

    public $showModal = false;
    public $showDeleteModal = false;
    public $deleteId = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'duration_days' => 'required|integer|min:1',
            'max_clinics' => 'required|integer|min:0',
            'max_labs' => 'required|integer|min:0',
            'max_doctors' => 'required|integer|min:0',
            'max_staff' => 'required|integer|min:0',
            'max_patients_per_month' => 'nullable|integer|min:0',
            'storage_limit_mb' => 'nullable|integer|min:0',
            'api_access' => 'boolean',
            'white_label' => 'boolean',
            'advanced_reporting' => 'boolean',
            'sms_notifications' => 'boolean',
            'telemedicine' => 'boolean',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $package = Package::findOrFail($id);
        $this->packageId = $package->id;
        $this->name = $package->name;
        $this->description = $package->description;
        $this->price = $package->price;
        $this->billing_cycle = $package->billing_cycle;
        $this->duration_days = $package->duration_days;
        $this->max_clinics = $package->max_clinics;
        $this->max_labs = $package->max_labs;
        $this->max_doctors = $package->max_doctors;
        $this->max_staff = $package->max_staff;
        $this->max_patients_per_month = $package->max_patients_per_month;
        $this->storage_limit_mb = $package->storage_limit_mb;
        $this->api_access = $package->api_access;
        $this->white_label = $package->white_label;
        $this->advanced_reporting = $package->advanced_reporting;
        $this->sms_notifications = $package->sms_notifications;
        $this->telemedicine = $package->telemedicine;
        $this->is_active = $package->is_active;
        $this->is_approved = $package->is_approved;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'billing_cycle' => $this->billing_cycle,
            'duration_days' => $this->duration_days,
            'max_clinics' => $this->max_clinics,
            'max_labs' => $this->max_labs,
            'max_doctors' => $this->max_doctors,
            'max_staff' => $this->max_staff,
            'max_patients_per_month' => $this->max_patients_per_month,
            'storage_limit_mb' => $this->storage_limit_mb,
            'api_access' => $this->api_access,
            'white_label' => $this->white_label,
            'advanced_reporting' => $this->advanced_reporting,
            'sms_notifications' => $this->sms_notifications,
            'telemedicine' => $this->telemedicine,
            'is_active' => $this->is_active,
        ];

        // is_approved can only be set by super admin, usually via a separate action, 
        // but it's here in the form too. 
        $data['is_approved'] = $this->is_approved;

        if ($this->packageId) {
            Package::findOrFail($this->packageId)->update($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Package updated successfully']);
        } else {
            Package::create($data);
            $this->dispatch('toast', ['type' => 'success', 'message' => 'Package created successfully']);
        }

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
        $package = Package::findOrFail($this->deleteId);
        
        if ($package->clinics()->count() > 0) {
            $this->dispatch('toast', ['type' => 'error', 'message' => 'Cannot delete package with active clinics']);
            $this->showDeleteModal = false;
            return;
        }

        $package->delete();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Package deleted successfully']);
        $this->showDeleteModal = false;
    }

    public function toggleStatus($id)
    {
        $package = Package::findOrFail($id);
        $package->update(['is_active' => !$package->is_active]);
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Package status updated to ' . ($package->is_active ? 'Active' : 'Inactive')]);
    }

    public function approve($id)
    {
        $package = Package::findOrFail($id);
        $package->update(['is_approved' => true]);
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Package approved successfully']);
    }

    public function resetForm()
    {
        $this->packageId = null;
        $this->name = '';
        $this->description = '';
        $this->price = '';
        $this->billing_cycle = 'monthly';
        $this->duration_days = 30;
        $this->max_clinics = 1;
        $this->max_labs = 0;
        $this->max_doctors = 1;
        $this->max_staff = 5;
        $this->max_patients_per_month = 100;
        $this->storage_limit_mb = 1024;
        $this->api_access = false;
        $this->white_label = false;
        $this->advanced_reporting = false;
        $this->sms_notifications = false;
        $this->telemedicine = false;
        $this->is_active = true;
        $this->is_approved = false;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.super-admin.packages', [
            'packages' => Package::withCount('clinics')->latest()->paginate(10),
        ]);
    }
}