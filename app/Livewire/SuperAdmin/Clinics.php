<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Clinic;

class Clinics extends Component
{
    use WithPagination;

    public $pageTitle = 'Manage Clinics';
    public $search = '';

    public function render()
    {
        return view('livewire.super-admin.clinics', [
            'clinics' => Clinic::with(['admin', 'package'])
                ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
                ->paginate(10),
        ]);
    }
}