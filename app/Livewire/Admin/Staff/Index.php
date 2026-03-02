<?php

namespace App\Livewire\Admin\Staff;

use App\Models\Clinic;
use App\Models\StaffProfile;
use App\Services\StaffService;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Staff — Clinix')]
class Index extends Component
{
    use WithPagination;

    public Clinic $clinic;
    public string $roleFilter   = '';
    public string $search       = '';
    public bool   $showRemoveModal = false;
    public ?int   $removingId   = null;

    public function mount(int $clinicId): void
    {
        $this->clinic = Clinic::where('id', $clinicId)
            ->where('owner_id', auth()->id())
            ->firstOrFail();
    }

    public function updatingSearch(): void { $this->resetPage(); }

    public function confirmRemove(int $id): void
    {
        $this->removingId      = $id;
        $this->showRemoveModal = true;
    }

    public function removeStaff(StaffService $service): void
    {
        $profile = StaffProfile::where('id', $this->removingId)
            ->where('clinic_id', $this->clinic->id)
            ->firstOrFail();

        $service->removeStaff($profile);
        $this->showRemoveModal = false;
        $this->dispatch('toast', message: 'Staff member removed.');
    }

    public function render(StaffService $service)
    {
        $staff = StaffProfile::with(['user.roles', 'department'])
            ->where('clinic_id', $this->clinic->id)
            ->when($this->roleFilter, fn($q) =>
                $q->whereHas('user.roles', fn($r) => $r->where('name', $this->roleFilter))
            )
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name','like',"%{$this->search}%")
                      ->orWhere('email','like',"%{$this->search}%")
                )
            )
            ->latest()
            ->paginate(15);

        $stats = $service->getStaffStats($this->clinic->id);

        return view('livewire.admin.staff.index', compact('staff', 'stats'));
    }
}
