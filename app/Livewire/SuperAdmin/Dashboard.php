<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\Package;
use App\Models\Clinic;
use App\Models\User;
use App\Models\Staff;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $pageTitle = 'Super Admin Dashboard';
    
    public $stats = [];
    public $recentClinics = [];
    public $healthAlerts = [];
    public $revenueChart = [];

    public function mount()
    {
        $this->loadStats();
        $this->loadRecentClinics();
        $this->loadRevenueChart();
    }

    public function loadStats()
    {
        $this->stats = [
            'total_clinics' => Clinic::count(),
            'active_clinics' => Clinic::where('status', 'active')->count(),
            'total_packages' => Package::where('is_approved', true)->count(),
            'total_revenue' => Clinic::join('packages', 'clinics.package_id', '=', 'packages.id')->sum('packages.price'),
            
            // Platform Wide Granular Stats
            'total_admins' => User::role('clinic-admin')->count(),
            'total_doctors' => Staff::where('role', 'doctor')->count(),
            'total_patients' => \App\Models\Patient::count(),
            'total_appointments' => \App\Models\Appointment::count(),
            
            'new_clinics_this_month' => Clinic::whereMonth('created_at', now()->month)->count(),
            'expiring_soon' => Clinic::where('package_expires_at', '<=', now()->addDays(7))
                ->where('package_expires_at', '>=', now())
                ->count(),
        ];
    }

    public function loadRecentClinics()
    {
        // Enrich recent clinics with more metadata for the dashboard
        $this->recentClinics = Clinic::with(['admin', 'package'])
            ->withCount(['staff as doctor_count' => function($query) {
                $query->where('role', 'doctor');
            }])
            ->withCount('staff')
            ->withCount('appointments')
            ->withCount('patients')
            ->latest()
            ->take(6)
            ->get();
    }

    public function loadRevenueChart()
    {
        // Monthly revenue for the last 6 months
        $months = collect(range(5, 0))->map(function ($i) {
            return now()->subMonths($i)->format('M Y');
        });

        $baseline = collect(range(5, 0))->map(function ($i) {
            return Clinic::join('packages', 'clinics.package_id', '=', 'packages.id')
                ->whereMonth('clinics.created_at', now()->subMonths($i)->month)
                ->sum('packages.price');
        });

        // Add a second dataset for comparison (e.g., Target Revenue or Previous period)
        $this->revenueChart = [
            'labels' => $months,
            'current' => $baseline,
            'previous' => $baseline->map(fn($val) => $val * 0.85), // Mock data for premium feel
        ];

        // Prepare health alerts
        $this->healthAlerts = Clinic::where('status', '!=', 'active')
            ->latest()
            ->take(3)
            ->get();
    }

    public function render()
    {
        return view('livewire.super-admin.dashboard');
    }
}