<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\User;
use App\Models\Module;

class AdminModules extends Component
{
    public $user;
    public $pageTitle = 'Manage Admin Modules';

    public function mount(User $user)
    {
        $this->user = $user;
        
        // Ensure this user has the clinic-admin role (security check)
        if (!$user->hasRole('clinic-admin')) {
             session()->flash('error', 'Module management is only available for Clinic Admins.');
             return redirect()->route('super-admin.clinics');
        }
    }

    public function toggleModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        
        if ($module->is_core) {
            session()->flash('error', 'Core modules cannot be disabled.');
            return;
        }

        $exists = $this->user->modules()->where('module_id', $moduleId)->exists();

        if ($exists) {
            $pivot = $this->user->modules()->where('module_id', $moduleId)->first()->pivot;
            $this->user->modules()->updateExistingPivot($moduleId, [
                'is_active' => !$pivot->is_active
            ]);
        } else {
            // Apply override: if global is active, we disable it for this user.
            $this->user->modules()->attach($moduleId, [
                'is_active' => !$module->is_active
            ]);
        }

        session()->flash('success', "Module '{$module->name}' updated for {$this->user->name}. This reflects across all their clinics.");
    }

    public function render()
    {
        return view('livewire.super-admin.admin-modules', [
            'modules' => Module::orderBy('name')->get()
        ]);
    }
}
