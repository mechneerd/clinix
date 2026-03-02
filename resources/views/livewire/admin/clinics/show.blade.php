<div class="p-6 lg:p-8 space-y-6">

    {{-- Header with clinic identity --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="h-3" style="background: linear-gradient(135deg, {{ $clinic->primary_color }}, {{ $clinic->secondary_color }})"></div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-5">
                @if ($clinic->logo_url)
                    <img src="{{ $clinic->logo_url }}" class="w-16 h-16 rounded-2xl object-cover shadow-md" />
                @else
                    <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-md"
                         style="background: linear-gradient(135deg, {{ $clinic->primary_color }}, {{ $clinic->secondary_color }})">
                        {{ strtoupper(substr($clinic->name, 0, 2)) }}
                    </div>
                @endif
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-1">
                        <h1 class="text-xl font-bold text-slate-900 dark:text-white">{{ $clinic->name }}</h1>
                        <span @class(['text-xs px-2.5 py-0.5 rounded-full font-medium',
                                      'bg-green-100 text-green-700' => $clinic->is_active,
                                      'bg-slate-100 text-slate-500' => !$clinic->is_active])>
                            {{ $clinic->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        @if ($clinic->is_verified)
                            <span class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-blue-100 text-blue-700">✓ Verified</span>
                        @endif
                    </div>
                    <p class="text-slate-500 text-sm">{{ $clinic->address }}, {{ $clinic->city }}, {{ $clinic->state }}</p>
                    <div class="flex flex-wrap gap-4 mt-2 text-sm text-slate-600 dark:text-slate-400">
                        @if ($clinic->phone)<span class="flex items-center gap-1"><flux:icon name="phone" class="w-3.5 h-3.5"/>{{ $clinic->phone }}</span>@endif
                        @if ($clinic->email)<span class="flex items-center gap-1"><flux:icon name="envelope" class="w-3.5 h-3.5"/>{{ $clinic->email }}</span>@endif
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.clinics.index') }}" wire:navigate>
                        <flux:button size="sm" variant="ghost" class="border-slate-200 dark:border-slate-700 rounded-xl" icon="arrow-left">Back</flux:button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ([
            ['label'=>'Today\'s Appts', 'value'=>$stats['appointments_today'], 'icon'=>'calendar-days',   'color'=>'indigo'],
            ['label'=>'This Month',     'value'=>$stats['appointments_month'], 'icon'=>'chart-bar',        'color'=>'blue'],
            ['label'=>'Total Patients', 'value'=>$stats['total_patients'],     'icon'=>'user-group',       'color'=>'emerald'],
            ['label'=>'Pending',        'value'=>$stats['pending'],            'icon'=>'clock',            'color'=>'amber'],
        ] as $s)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
                <div class="w-9 h-9 rounded-xl bg-{{ $s['color'] }}-50 dark:bg-{{ $s['color'] }}-900/20 flex items-center justify-center mb-3">
                    <flux:icon :name="$s['icon']" class="w-5 h-5 text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400" />
                </div>
                <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $s['value'] }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $s['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Management Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ([
            ['icon'=>'user-group',       'label'=>'Departments',  'desc'=>$clinic->departments_count.' departments', 'color'=>'blue',   'route'=>route('admin.departments.index', $clinic->id)],
            ['icon'=>'users',            'label'=>'Staff & Doctors','desc'=>$clinic->staff_count.' members',       'color'=>'indigo',  'route'=>route('admin.staff.index', $clinic->id)],
            ['icon'=>'beaker',           'label'=>'Laboratories', 'desc'=>$clinic->labs_count.' labs',            'color'=>'violet',  'route'=>route('admin.labs.index', $clinic->id)],
            ['icon'=>'shopping-bag',     'label'=>'Pharmacies',   'desc'=>$clinic->pharmacies_count.' pharmacies','color'=>'emerald', 'route'=>route('admin.pharmacies.index', $clinic->id)],
            ['icon'=>'calendar-days',    'label'=>'Appointments', 'desc'=>'View & manage',                       'color'=>'amber',   'route'=>'#'],
            ['icon'=>'cog-6-tooth',      'label'=>'Settings',     'desc'=>'Clinic configuration',               'color'=>'slate',   'route'=>'#'],
        ] as $card)
            <a href="{{ $card['route'] }}" wire:navigate
               class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 hover:shadow-md hover:border-{{ $card['color'] }}-300 dark:hover:border-{{ $card['color'] }}-700 transition-all group">
                <div class="w-12 h-12 rounded-2xl bg-{{ $card['color'] }}-50 dark:bg-{{ $card['color'] }}-900/20 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <flux:icon :name="$card['icon']" class="w-6 h-6 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400" />
                </div>
                <h3 class="font-semibold text-slate-900 dark:text-white mb-0.5">{{ $card['label'] }}</h3>
                <p class="text-sm text-slate-500">{{ $card['desc'] }}</p>
                <div class="mt-3 flex items-center text-xs text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400 font-medium group-hover:gap-2 transition-all">
                    Manage <flux:icon name="arrow-right" class="w-3.5 h-3.5 ml-1" />
                </div>
            </a>
        @endforeach
    </div>

    {{-- Today's Appointments --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-800">
            <h2 class="font-semibold text-slate-900 dark:text-white text-sm">Today's Appointments</h2>
            <a href="#" class="text-xs text-indigo-600">View all →</a>
        </div>
        @if ($todayAppointments->isEmpty())
            <div class="py-10 text-center text-slate-500 text-sm">No appointments today</div>
        @else
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @foreach ($todayAppointments as $appt)
                    <div class="flex items-center gap-4 px-6 py-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-xs font-bold text-emerald-600">
                            {{ strtoupper(substr($appt->patient->name ?? 'P', 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-900 dark:text-white truncate">{{ $appt->patient->name ?? 'N/A' }}</p>
                            <p class="text-xs text-slate-500">Dr. {{ $appt->doctor->name ?? 'N/A' }} · {{ \Carbon\Carbon::parse($appt->start_time)->format('h:i A') }}</p>
                        </div>
                        <span @class(['text-xs px-2.5 py-0.5 rounded-full font-medium',
                                      'bg-amber-100 text-amber-700' => $appt->status === 'pending',
                                      'bg-blue-100 text-blue-700'   => $appt->status === 'confirmed',
                                      'bg-green-100 text-green-700' => $appt->status === 'completed'])>
                            {{ ucfirst($appt->status) }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
