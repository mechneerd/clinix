<?php

namespace App\Livewire\Patient;

use App\Services\LabService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.patient')]
#[Title('Lab Orders — Clinix')]
class LabOrders extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public ?int   $viewingId    = null;

    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function viewOrder(int $id): void { $this->viewingId = $id; }
    public function closeOrder(): void { $this->viewingId = null; }

    public function render(LabService $service)
    {
        $orders = $service->getPatientOrders(auth()->id(), $this->statusFilter ?: null);
        $detail = $this->viewingId ? $service->getOrderDetail($this->viewingId, auth()->id()) : null;

        return view('livewire.patient.lab-orders', compact('orders', 'detail'));
    }
}