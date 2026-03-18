<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Module;

class Modules extends Component
{
    public $pageTitle = 'Global Module Management';

    public function toggleModule($moduleId)
    {
        $module = Module::findOrFail($moduleId);
        
        // Prevent disabling core modules
        if ($module->is_core && $module->is_active) {
            session()->flash('error', 'Core modules cannot be disabled.');
            return;
        }

        $module->update([
            'is_active' => !$module->is_active
        ]);

        session()->flash('success', "Module '{$module->name}' updated successfully.");
    }

    public function render()
    {
        return view('livewire.super-admin.modules', [
            'modules' => Module::orderBy('name')->get()
        ]);
    }
}
