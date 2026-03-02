<div class="p-6 lg:p-8 space-y-8">

    {{-- Welcome Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }},
                {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">
                {{ now()->format('l, F j, Y') }} · Here's your clinic overview
            </p>
        </div>

        <div class="flex items-center gap-3">
            @if ($subscription)
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-200 dark:border-indigo-700">
                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                    <span class="text-sm font-medium text-indigo-700 dark:text-indigo-300">
                        {{ $subscription->tier->name }} Plan
                    </span>
                </div>
            @endif
            <a href="{{ route('admin.clinics.create') }}" wire:navigate>
                <flux:button variant="primary" icon="plus" size="sm"
                             class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl">
                    New Clinic
                </flux:button>
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Clinics --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="flex items-center gap-1 text-xs text-green-600 dark:text-green-400 font-medium">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    Active
                </span>
            </div>
            <div class="text-3xl font-bold text-slate-900 dark:text-white mb-1">{{ $stats['total_clinics'] }}</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Total Clinics</div>
        </div>

        {{-- Patients --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-emerald-50 dark:bg-emerald-900/40 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium bg-emerald-50 dark:bg-emerald-900/30 px-2 py-0.5 rounded-full">+12%</span>
            </div>
            <div class="text-3xl font-bold text-slate-900 dark:text-white mb-1">{{ number_format($stats['total_patients']) }}</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Total Patients</div>
        </div>

        {{-- Today's Appointments --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-5 border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-amber-50 dark:bg-amber-900/40 flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-xs text-amber-600 font-medium bg-amber-50 dark:bg-amber-900/30 px-2 py-0.5 rounded-full">Today</span>
            </div>
            <div class="text-3xl font-bold text-slate-900 dark:text-white mb-1">{{ $stats['appointments_today'] }}</div>
            <div class="text-sm text-slate-500 dark:text-slate-400">Appointments</div>
        </div>

        {{-- Revenue --}}
        <div class="bg-gradient-to-br from-indigo-500 to-violet-600 rounded-2xl p-5 shadow-lg shadow-indigo-500/20">
            <div class="flex items-center justify-between mb-4">
                <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs text-indigo-100 font-medium">This month</span>
            </div>
            <div class="text-3xl font-bold text-white mb-1">${{ number_format($stats['monthly_revenue'], 0) }}</div>
            <div class="text-sm text-indigo-200">Monthly Revenue</div>
        </div>

    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- My Clinics --}}
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <div class="flex items-center justify-between p-5 border-b border-slate-100 dark:border-slate-800">
                <h2 class="font-semibold text-slate-900 dark:text-white">My Clinics</h2>
                <a href="{{ route('admin.clinics.create') }}" wire:navigate>
                    <flux:button variant="ghost" size="xs" icon="plus">
                        Add Clinic
                    </flux:button>
                </a>
            </div>

            @if ($clinics->isEmpty())
                <div class="py-16 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                        </svg>
                    </div>
                    <h3 class="text-slate-900 dark:text-white font-semibold mb-1">No clinics yet</h3>
                    <p class="text-slate-500 text-sm mb-4">Create your first clinic to get started</p>
                    <a href="{{ route('admin.clinics.create') }}" wire:navigate>
                        <flux:button variant="primary" size="sm"
                                     class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl">
                            Create Clinic
                        </flux:button>
                    </a>
                </div>
            @else
                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach ($clinics as $clinic)
                        <div class="flex items-center gap-4 p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <img src="{{ $clinic->logo_url }}" alt="{{ $clinic->name }}"
                                 class="w-10 h-10 rounded-xl object-cover" />
                            <div class="flex-1 min-w-0">
                                <div class="font-medium text-slate-900 dark:text-white truncate">{{ $clinic->name }}</div>
                                <div class="text-sm text-slate-500 truncate">{{ $clinic->city }}, {{ $clinic->country }}</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span @class([
                                    'text-xs px-2 py-0.5 rounded-full font-medium',
                                    'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $clinic->status === 'active',
                                    'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' => $clinic->status !== 'active',
                                ])>
                                    {{ ucfirst($clinic->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sidebar Widgets --}}
        <div class="space-y-5">

            {{-- Subscription --}}
            @if ($subscription)
            <div class="bg-gradient-to-br from-slate-900 to-indigo-950 rounded-2xl p-5 border border-indigo-900">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-white text-sm">Your Plan</h3>
                    <span class="text-xs text-indigo-300 font-medium px-2 py-0.5 rounded-full bg-indigo-500/20 border border-indigo-500/30">
                        {{ ucfirst($subscription->billing_cycle) }}
                    </span>
                </div>
                <div class="text-2xl font-bold text-white mb-1">{{ $subscription->tier->name }}</div>
                <div class="text-sm text-slate-400 mb-4">
                    Renews {{ $subscription->ends_at?->format('M d, Y') ?? 'Never' }}
                </div>
                <div class="space-y-2 mb-4">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-400">Clinics</span>
                        <span class="text-white">{{ $clinics->count() }} / {{ $subscription->tier->max_clinics }}</span>
                    </div>
                    <div class="w-full h-1.5 bg-white/10 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full transition-all"
                             style="width: {{ min(100, ($clinics->count() / max(1, $subscription->tier->max_clinics)) * 100) }}%"></div>
                    </div>
                </div>
                <flux:button variant="ghost" size="xs" class="w-full text-indigo-300 border-indigo-800 hover:bg-indigo-900/50">
                    Upgrade Plan
                </flux:button>
            </div>
            @endif

            {{-- Quick Actions --}}
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5">
                <h3 class="font-semibold text-slate-900 dark:text-white text-sm mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    @foreach([
                        ['icon' => 'calendar-days', 'label' => 'New Appointment', 'color' => 'text-indigo-500'],
                        ['icon' => 'user-plus',     'label' => 'Add Patient',      'color' => 'text-emerald-500'],
                        ['icon' => 'document-text', 'label' => 'Create Report',    'color' => 'text-amber-500'],
                        ['icon' => 'bell',          'label' => 'Send Notification','color' => 'text-violet-500'],
                    ] as $action)
                        <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors text-left">
                            <flux:icon :name="$action['icon']" class="w-5 h-5 {{ $action['color'] }}" />
                            <span class="text-sm text-slate-700 dark:text-slate-300">{{ $action['label'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

</div>