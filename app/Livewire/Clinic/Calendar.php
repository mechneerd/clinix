<?php

namespace App\Livewire\Clinic;

use Livewire\Component;
use App\Models\Appointment;
use Carbon\Carbon;

class Calendar extends Component
{
    public $currentMonth;
    public $currentYear;
    public $daysInMonth = [];
    public $appointments = [];
    public $clinic;

    public function mount()
    {
        $this->clinic = auth()->user()->clinic;
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadCalendar();
    }

    public function loadCalendar()
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $this->daysInMonth = [];
        
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

        for ($i = 1; $i <= $date->daysInMonth; $i++) {
            $this->daysInMonth[] = [
                'day' => $i,
                'month' => $this->currentMonth,
                'year' => $this->currentYear,
                'isCurrentMonth' => true,
                'fullDate' => (clone $date)->day($i)->toDateString()
            ];
        }

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
        if (!$this->clinic) return;

        $startDate = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth()->subDays(7);
        $endDate = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth()->addDays(7);

        $this->appointments = Appointment::with(['patient', 'doctor.user'])
            ->where('clinic_id', $this->clinic->id)
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

    public function render()
    {
        return view('livewire.clinic.calendar');
    }
}
