<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Models\Clinic;
use Livewire\WithPagination;

class BrowseClinics extends Component
{
    use WithPagination;

    public $search = '';
    public $city = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $clinics = Clinic::where('status', 'active')
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->city, function($q) {
                $q->where('city', 'like', '%' . $this->city . '%');
            })
            ->paginate(12);

        return view('livewire.patient.browse-clinics', [
            'clinics' => $clinics
        ]);
    }
}
