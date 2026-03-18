<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Models\Appointment;
use Carbon\Carbon;

class Calendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $daysInMonth = [];
    public $appointments = [];

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadCalendar();
    }

    public function loadCalendar()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $this->daysInMonth = [];
        
        // Fill previous month days (to start on correct weekday)
        $startDayOfWeek = $date->dayOfWeek;
        $prevMonthDate = (clone $date)->subMonth();
        $daysInPrevMonth = $prevMonthDate->daysInMonth;
        for ($i = $startDayOfWeek - 1; $i >= 0; $i--) {
            $this->daysInMonth[] = [
                'day' => $daysInPrevMonth - $i,
                'month' => $prevMonthDate->month,
                'year' => $prevMonthDate->year,
                'isCurrentMonth' => false,
                'fullDate' => (clone $prevMonthDate)->day($daysInPrevMonth - $i)->toDateString()
            ];
        }

        // Fill current month days
        for ($i = 1; $i <= $date->daysInMonth; $i++) {
            $this->daysInMonth[] = [
                'day' => $i,
                'month' => $this->currentMonth,
                'year' => $this->currentYear,
                'isCurrentMonth' => true,
                'fullDate' => (clone $date)->day($i)->toDateString()
            ];
        }

        // Fill next month days
        $endDayOfWeek = (clone $date)->endOfMonth()->dayOfWeek;
        $nextMonthDate = (clone $date)->addMonth();
        for ($i = 1; $i <= (6 - $endDayOfWeek); $i++) {
            $this->daysInMonth[] = [
                'day' => $i,
                'month' => $nextMonthDate->month,
                'year' => $nextMonthDate->year,
                'isCurrentMonth' => false,
                'fullDate' => (clone $nextMonthDate)->day($i)->toDateString()
            ];
        }

        $this->loadAppointments();
    }

    public function loadAppointments()
    {
        $patient = auth()->user()->patient;
        if (!$patient) return;

        $startDate = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth()->subDays(7);
        $endDate = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth()->addDays(7);

        $this->appointments = Appointment::with(['clinic', 'doctor.user'])
            ->where('patient_id', $patient->id)
            ->whereBetween('appointment_date', [$startDate, $endDate])
            ->get()
            ->groupBy(fn($app) => $app->appointment_date->toDateString())
            ->toArray();
    }

    public function prevMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadCalendar();
    }

    public function nextMonth()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadCalendar();
    }

    public function setReminder($appointmentId, $minutes)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        
        // Security check
        if ($appointment->patient_id !== auth()->user()->patient->id) {
            return;
        }

        $appointment->update(['reminder_minutes' => $minutes]);
        $this->loadAppointments();
        $this->dispatch('toast', ['type' => 'success', 'message' => 'Reminder set successfully.']);
    }

    public function render()
    {
        return view('livewire.patient.calendar')
            ->layout('components.layouts.landing');
    }
}
