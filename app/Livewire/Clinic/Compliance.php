<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use App\Models\Incident;
use App\Models\SystemAuditLog;

class Compliance extends Component
{
    public function render()
    {
        $clinicId = auth()->user()->staff->clinic_id;

        return view('livewire.clinic.compliance', [
            'totalIncidents' => Incident::where('clinic_id', $clinicId)->count(),
            'unresolvedIncidents' => Incident::where('clinic_id', $clinicId)->where('status', '!=', 'resolved')->count(),
            'recentIncidents' => Incident::where('clinic_id', $clinicId)->latest()->take(5)->get(),
            'auditLogs' => SystemAuditLog::with('user')
                ->where('action', '!=', 'login') // Filter noise
                ->latest()->take(10)->get(),
        ]);
    }
}
