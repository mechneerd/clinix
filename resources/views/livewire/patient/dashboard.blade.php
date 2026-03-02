<div class="p-6 lg:p-8 space-y-8">

    {{-- Welcome --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                Hello, {{ $user->name }} 👋
            </h1>
            <p class="text-slate-500 text-sm mt-1">{{ now()->format('l, F j, Y') }} · Your health at a glance</p>
        </div>
        <a href="{{ route('patient.book-appointment') }}" wire:navigate>
            <flux:button variant="primary"
                         class="bg-emerald-600 hover:bg-emerald-700 text-white border-0 rounded-xl">
                + Book Appointment
            </flux:button>
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ([
            ['label' => 'Total Appointments', 'value' => $stats['total_appointments'], 'icon' => 'calendar-days',        'color' => 'emerald'],
            ['label' => 'Upcoming',           'value' => $stats['upcoming'],           'icon' => 'clock',               'color' => 'blue'],
            ['label' => 'Lab Orders',         'value' => $stats['lab_orders'],         'icon' => 'beaker',              'color' => 'violet'],
            ['label' => 'Prescriptions',      'value' => $stats['prescriptions'],      'icon' => 'document-text',       'color' => 'amber'],
        ] as $stat)
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] }}-50 dark:bg-{{ $stat['color'] }}-900/30 flex items-center justify-center mb-3">
                <flux:icon :name="$stat['icon']" class="w-5 h-5 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400" />
            </div>
            <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $stat['value'] }}</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Upcoming Appointments --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100 dark:border-slate-800">
                <h2 class="font-semibold text-slate-900 dark:text-white text-sm">Upcoming Appointments</h2>
                <a href="{{ route('patient.appointments') }}" wire:navigate
                   class="text-xs text-emerald-600 hover:text-emerald-700">View all →</a>
            </div>

            @if ($upcoming->isEmpty())
                <div class="py-16 text-center">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <flux:icon name="calendar-days" class="w-7 h-7 text-slate-400" />
                    </div>
                    <h3 class="font-semibold text-slate-900 dark:text-white mb-1">No upcoming appointments</h3>
                    <p class="text-slate-500 text-sm mb-4">Book one today to stay healthy!</p>
                    <a href="{{ route('patient.book-appointment') }}" wire:navigate>
                        <flux:button size="sm" class="bg-emerald-600 text-white border-0 rounded-xl">Book Now</flux:button>
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($upcoming as $appt)
                        <div class="flex items-center gap-4 p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            {{-- Date block --}}
                            <div class="flex-shrink-0 w-12 text-center">
                                <div class="text-lg font-bold text-emerald-600">{{ $appt->appointment_date->format('d') }}</div>
                                <div class="text-xs text-slate-500 uppercase">{{ $appt->appointment_date->format('M') }}</div>
                            </div>

                            {{-- Doctor avatar --}}
                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-sm font-bold text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                                {{ strtoupper(substr($appt->doctor->name ?? 'D', 0, 2)) }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-slate-900 dark:text-white text-sm truncate">
                                    Dr. {{ $appt->doctor->name ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-slate-500">
                                    {{ $appt->clinic->name ?? '' }} · {{ \Carbon\Carbon::parse($appt->start_time)->format('h:i A') }}
                                </div>
                            </div>

                            <span @class([
                                'text-xs px-2.5 py-1 rounded-full font-medium flex-shrink-0',
                                'bg-amber-100 text-amber-700' => $appt->status === 'pending',
                                'bg-blue-100 text-blue-700'   => $appt->status === 'confirmed',
                            ])>
                                {{ ucfirst($appt->status) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Right: Health + Notifications --}}
        <div class="space-y-5">

            {{-- Health summary card --}}
            @if ($user->profile)
            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-5 text-white shadow-lg shadow-emerald-500/20">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-xl font-bold">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="font-bold text-sm">{{ $user->name }}</div>
                        <div class="text-xs text-emerald-100">Patient</div>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="bg-white/10 rounded-xl p-2.5">
                        <div class="text-emerald-100 mb-0.5">Blood Group</div>
                        <div class="font-bold text-sm">{{ $user->profile->blood_group ?? '—' }}</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5">
                        <div class="text-emerald-100 mb-0.5">Gender</div>
                        <div class="font-bold text-sm capitalize">{{ $user->profile->gender ?? '—' }}</div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5">
                        <div class="text-emerald-100 mb-0.5">Age</div>
                        <div class="font-bold text-sm">
                            {{ $user->profile->date_of_birth ? \Carbon\Carbon::parse($user->profile->date_of_birth)->age . ' yrs' : '—' }}
                        </div>
                    </div>
                    <div class="bg-white/10 rounded-xl p-2.5">
                        <div class="text-emerald-100 mb-0.5">DOB</div>
                        <div class="font-bold text-sm">{{ $user->profile->date_of_birth ? \Carbon\Carbon::parse($user->profile->date_of_birth)->format('d M Y') : '—' }}</div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Recent Lab Orders --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-slate-900 dark:text-white">Recent Lab Orders</h3>
                    <a href="{{ route('patient.lab-orders') }}" wire:navigate class="text-xs text-emerald-600">View all</a>
                </div>
                @forelse ($recentLabs as $lab)
                    <div class="flex items-center gap-3 mb-3 last:mb-0">
                        <div class="w-8 h-8 rounded-lg bg-violet-50 dark:bg-violet-900/30 flex items-center justify-center">
                            <flux:icon name="beaker" class="w-4 h-4 text-violet-500" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-xs font-medium text-slate-900 dark:text-white truncate">{{ $lab->order_number }}</div>
                            <div class="text-xs text-slate-500">{{ $lab->created_at->format('M d') }}</div>
                        </div>
                        <span @class([
                            'text-xs px-2 py-0.5 rounded-full font-medium',
                            'bg-green-100 text-green-700' => $lab->status === 'completed',
                            'bg-amber-100 text-amber-700' => in_array($lab->status, ['ordered','in_progress']),
                        ])>{{ ucfirst(str_replace('_', ' ', $lab->status)) }}</span>
                    </div>
                @empty
                    <p class="text-xs text-slate-500 text-center py-4">No lab orders yet</p>
                @endforelse
            </div>

        </div>
    </div>

</div>
