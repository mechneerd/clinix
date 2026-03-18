<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use App\Models\Staff;
use App\Models\JobPosition;
use App\Models\EmployeeLeave;
use App\Models\Payroll;

class Workforce extends Component
{
    public function render()
    {
        $clinicId = auth()->user()->staff->clinic_id;

        return view('livewire.clinic.workforce', [
            'staffCount' => Staff::where('clinic_id', $clinicId)->count(),
            'positions' => JobPosition::where('clinic_id', $clinicId)->count(),
            'pendingLeaves' => EmployeeLeave::whereHas('staff', fn($q) => $q->where('clinic_id', $clinicId))
                ->where('status', 'pending')->count(),
            'totalPayroll' => Payroll::whereHas('staff', fn($q) => $q->where('clinic_id', $clinicId))
                ->where('month_year', now()->format('F Y'))->sum('net_salary'),
            'recentLeaves' => EmployeeLeave::with('staff.user')
                ->whereHas('staff', fn($q) => $q->where('clinic_id', $clinicId))
                ->latest()->take(5)->get(),
        ]);
    }
}
