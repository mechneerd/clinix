<div class="p-6 lg:p-8 space-y-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <div class="w-2 h-2 rounded-full bg-violet-400 animate-pulse"></div>
                <span class="text-xs text-violet-400 font-medium uppercase tracking-widest">Platform Control</span>
            </div>
            <h1 class="text-2xl font-bold text-white">Platform Overview</h1>
            <p class="text-slate-500 text-sm mt-1">{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div class="flex gap-3">
            <flux:button variant="ghost" size="sm" icon="arrow-down-tray"
                         class="border border-slate-700 text-slate-300">
                Export Report
            </flux:button>
            <flux:button size="sm" icon="plus"
                         class="bg-violet-600 hover:bg-violet-700 text-white border-0 rounded-xl">
                Add Plan
            </flux:button>
        </div>
    </div>

    {{-- KPI Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">

        {{-- Total Admins --}}
        <div class="xl:col-span-1 bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-violet-800 transition-colors">
            <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-white">{{ number_format($stats['total_admins']) }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Providers</div>
        </div>

        {{-- Total Patients --}}
        <div class="xl:col-span-1 bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-emerald-800 transition-colors">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-white">{{ number_format($stats['total_patients']) }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Patients</div>
        </div>

        {{-- Total Clinics --}}
        <div class="xl:col-span-1 bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-blue-800 transition-colors">
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-white">{{ number_format($stats['total_clinics']) }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Clinics</div>
        </div>

        {{-- Active Subscriptions --}}
        <div class="xl:col-span-1 bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-amber-800 transition-colors">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-white">{{ number_format($stats['active_subs']) }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Active Subs</div>
        </div>

        {{-- MRR --}}
        <div class="xl:col-span-1 bg-slate-900 border border-slate-800 rounded-2xl p-5 hover:border-green-800 transition-colors">
            <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-white">${{ number_format($stats['mrr'], 0) }}</div>
            <div class="text-xs text-slate-500 mt-0.5">MRR</div>
        </div>

        {{-- Total Revenue --}}
        <div class="xl:col-span-1 bg-gradient-to-br from-violet-600 to-purple-700 rounded-2xl p-5 shadow-lg shadow-violet-500/20">
            <div class="w-10 h-10 rounded-xl bg-white/15 flex items-center justify-center mb-3">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-2xl font-bold text-white">${{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="text-xs text-violet-200 mt-0.5">Total Revenue</div>
        </div>

    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Providers Table --}}
        <div class="lg:col-span-2 bg-slate-900 border border-slate-800 rounded-2xl overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-800">
                <h2 class="font-semibold text-white text-sm">Recent Healthcare Providers</h2>
                <a href="#" class="text-xs text-violet-400 hover:text-violet-300">View all →</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-800">
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Provider</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Plan</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Clinics</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Joined</th>
                            <th class="text-left px-6 py-3 text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800">
                        @forelse ($recentAdmins as $admin)
                            <tr class="hover:bg-slate-800/50 transition-colors">
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center text-xs font-bold text-white flex-shrink-0">
                                            {{ strtoupper(substr($admin->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-white">{{ $admin->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $admin->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    @if ($admin->activeSubscription)
                                        <span @class([
                                            'text-xs px-2 py-0.5 rounded-full font-medium',
                                            'bg-violet-500/15 text-violet-400 border border-violet-500/20' => $admin->activeSubscription->tier->slug === 'advanced',
                                            'bg-blue-500/15 text-blue-400 border border-blue-500/20' => $admin->activeSubscription->tier->slug === 'basic',
                                            'bg-slate-700 text-slate-400' => $admin->activeSubscription->tier->slug === 'free',
                                        ])>
                                            {{ $admin->activeSubscription->tier->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-600">No plan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3">
                                    <span class="text-sm text-slate-300">{{ $admin->clinics->count() }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="text-xs text-slate-500">{{ $admin->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-green-400">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                        Active
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-600 text-sm">
                                    No providers registered yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right sidebar --}}
        <div class="space-y-5">

            {{-- Plan Distribution --}}
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <h3 class="font-semibold text-white text-sm mb-4">Plan Distribution</h3>
                <div class="space-y-3">
                    @foreach ($tiers as $tier)
                        @php
                            $count     = $tier->user_subscriptions_count;
                            $total     = max(1, $stats['active_subs']);
                            $pct       = round(($count / $total) * 100);
                            $colors    = ['free' => 'bg-slate-600', 'basic' => 'bg-blue-500', 'advanced' => 'bg-violet-500'];
                            $barColor  = $colors[$tier->slug] ?? 'bg-indigo-500';
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs text-slate-400">{{ $tier->name }}</span>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-medium text-white">{{ $count }}</span>
                                    <span class="text-xs text-slate-600">{{ $pct }}%</span>
                                </div>
                            </div>
                            <div class="h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                <div class="h-full {{ $barColor }} rounded-full transition-all"
                                     style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                <h3 class="font-semibold text-white text-sm mb-4">Quick Actions</h3>
                <div class="space-y-1.5">
                    @foreach ([
                        ['icon' => 'tag',          'label' => 'Manage Plans',        'color' => 'text-violet-400'],
                        ['icon' => 'users',         'label' => 'View All Providers',  'color' => 'text-blue-400'],
                        ['icon' => 'shield-check',  'label' => 'Audit Logs',          'color' => 'text-amber-400'],
                        ['icon' => 'bell',          'label' => 'Broadcast Notice',    'color' => 'text-emerald-400'],
                        ['icon' => 'cog-6-tooth',   'label' => 'System Settings',     'color' => 'text-slate-400'],
                    ] as $action)
                        <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-800 transition-colors text-left">
                            <flux:icon :name="$action['icon']" class="w-4 h-4 {{ $action['color'] }}" />
                            <span class="text-sm text-slate-300">{{ $action['label'] }}</span>
                        </button>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

</div>
