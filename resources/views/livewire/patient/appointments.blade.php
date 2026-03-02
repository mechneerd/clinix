<div class="p-6 lg:p-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">My Appointments</h1>
            <p class="text-slate-500 text-sm mt-1">View and manage all your appointments</p>
        </div>
        <a href="{{ route('patient.book-appointment') }}" wire:navigate>
            <flux:button class="bg-emerald-600 hover:bg-emerald-700 text-white border-0 rounded-xl" icon="plus">
                Book New
            </flux:button>
        </a>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2">
        @foreach ([''=>'All', 'pending'=>'Pending', 'confirmed'=>'Confirmed', 'completed'=>'Completed', 'cancelled'=>'Cancelled'] as $val => $label)
            <button wire:click="$set('statusFilter', '{{ $val }}')"
                    @class([
                        'px-4 py-1.5 rounded-full text-sm font-medium transition-all',
                        'bg-emerald-600 text-white shadow-sm' => $statusFilter === $val,
                        'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700 hover:border-emerald-400' => $statusFilter !== $val,
                    ])>
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Appointments --}}
    <div class="space-y-3">
        @forelse ($appointments as $appt)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-5">

                    {{-- Date --}}
                    <div class="flex-shrink-0 w-16 h-16 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex flex-col items-center justify-center border border-emerald-100 dark:border-emerald-900">
                        <span class="text-2xl font-bold text-emerald-600">{{ $appt->appointment_date->format('d') }}</span>
                        <span class="text-xs text-emerald-500 uppercase font-medium">{{ $appt->appointment_date->format('M') }}</span>
                    </div>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $appt->appointment_number }}</span>
                            <span @class([
                                'text-xs px-2.5 py-0.5 rounded-full font-medium',
                                'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' => $appt->status === 'pending',
                                'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'     => $appt->status === 'confirmed',
                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $appt->status === 'completed',
                                'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'         => $appt->status === 'cancelled',
                                'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400'    => !in_array($appt->status, ['pending','confirmed','completed','cancelled']),
                            ])>{{ ucfirst(str_replace('_', ' ', $appt->status)) }}</span>
                            <span class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 px-2 py-0.5 rounded-full capitalize">
                                {{ str_replace('_', ' ', $appt->type) }}
                            </span>
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-slate-600 dark:text-slate-400">
                            <span class="flex items-center gap-1">
                                <flux:icon name="user" class="w-3.5 h-3.5" />
                                Dr. {{ $appt->doctor->name ?? 'N/A' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <flux:icon name="building-office-2" class="w-3.5 h-3.5" />
                                {{ $appt->clinic->name ?? 'N/A' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <flux:icon name="clock" class="w-3.5 h-3.5" />
                                {{ \Carbon\Carbon::parse($appt->start_time)->format('h:i A') }}
                            </span>
                        </div>
                        @if ($appt->symptoms)
                            <p class="text-xs text-slate-500 mt-1 truncate">Symptoms: {{ $appt->symptoms }}</p>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2 flex-shrink-0">
                        @if (in_array($appt->status, ['pending', 'confirmed']))
                            <flux:button size="xs" variant="ghost"
                                         wire:click="openCancelModal({{ $appt->id }})"
                                         class="text-red-500 hover:bg-red-50 border-red-200 dark:border-red-900">
                                Cancel
                            </flux:button>
                        @endif
                        @if ($appt->status === 'completed' && $appt->visit)
                            <a href="{{ route('patient.reports') }}" wire:navigate>
                                <flux:button size="xs" variant="ghost"
                                             class="text-emerald-600 border-emerald-200 hover:bg-emerald-50">
                                    View Report
                                </flux:button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    <flux:icon name="calendar-days" class="w-8 h-8 text-slate-400" />
                </div>
                <h3 class="font-semibold text-slate-900 dark:text-white mb-2">No appointments found</h3>
                <p class="text-slate-500 text-sm mb-4">
                    {{ $statusFilter ? 'No ' . $statusFilter . ' appointments found.' : 'You have no appointments yet.' }}
                </p>
                <a href="{{ route('patient.book-appointment') }}" wire:navigate>
                    <flux:button class="bg-emerald-600 text-white border-0 rounded-xl">Book Your First Appointment</flux:button>
                </a>
            </div>
        @endforelse
    </div>

    {{ $appointments->links() }}

    {{-- Cancel Modal --}}
    @if ($showCancelModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-md p-6" wire:click.stop>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1">Cancel Appointment</h3>
                <p class="text-slate-500 text-sm mb-4">Please provide a reason for cancellation.</p>

                <flux:textarea wire:model="cancelReason" placeholder="Reason for cancellation…" rows="3"
                               class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-xl" />
                @error('cancelReason') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror

                <div class="flex gap-3 mt-4">
                    <flux:button wire:click="$set('showCancelModal', false)" variant="ghost"
                                 class="flex-1 border-slate-200 dark:border-slate-700">
                        Keep Appointment
                    </flux:button>
                    <flux:button wire:click="confirmCancel"
                                 class="flex-1 bg-red-600 hover:bg-red-700 text-white border-0"
                                 wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="confirmCancel">Yes, Cancel</span>
                        <span wire:loading wire:target="confirmCancel">Cancelling…</span>
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

</div>
