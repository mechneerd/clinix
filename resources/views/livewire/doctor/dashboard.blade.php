<div class="space-y-6 pb-20">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-brand-teal/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                    <span class="text-brand-teal">Clinical Hub</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    <span>Dashboard</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none">Welcome, Dr. <span class="text-brand-teal">{{ explode(' ', auth()->user()->name)[0] }}</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-3 font-medium text-sm">You have <span class="text-brand-green font-black">{{ $waitingPatients }} patients</span> in your session queue today.</p>
            </div>
            <div class="flex gap-3">
                <button class="px-6 py-3 bg-white dark:bg-slate-800 text-slate-700 dark:text-white rounded-2xl font-bold border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all text-sm shadow-sm">
                    View My Schedule
                </button>
            </div>
        </div>
    </div>

    <!-- Live Performance Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach([
            ['label' => 'Waiting Now', 'value' => $waitingPatients, 'icon' => 'users', 'color' => 'amber', 'subtitle' => 'LIVE QUEUE'],
            ['label' => 'Attended Today', 'value' => $patientsAttendedToday, 'icon' => 'check', 'color' => 'brand-green', 'subtitle' => "TODAY'S METRIC"],
            ['label' => 'Lab Scopes', 'value' => $pendingPrescriptions, 'icon' => 'pill', 'color' => 'brand-teal', 'subtitle' => 'PENDING DISPENSE'],
            ['label' => 'Clinical Hours', 'value' => '4.5', 'icon' => 'clock', 'color' => 'brand-teal', 'subtitle' => 'ON DUTY'],
        ] as $stat)
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm group hover:border-brand-teal/50 transition-all">
            <div class="w-12 h-12 rounded-2xl bg-{{ $stat['color'] === 'amber' ? 'amber-100 dark:bg-amber-500/10' : ($stat['color'] === 'brand-green' ? 'emerald-100 dark:bg-emerald-500/10' : 'slate-100 dark:bg-slate-800') }} flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                <x-icons :name="$stat['icon']" class="w-6 h-6 text-{{ $stat['color'] === 'amber' ? 'amber-600 dark:text-amber-400' : ($stat['color'] === 'brand-green' ? 'emerald-600 dark:text-emerald-400' : 'brand-teal') }}" />
            </div>
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">{{ $stat['label'] }}</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white leading-none">{{ $stat['value'] }}</h3>
            <p class="text-[9px] text-{{ $stat['color'] === 'amber' ? 'amber-500' : ($stat['color'] === 'brand-green' ? 'emerald-500' : 'brand-teal') }} font-black mt-3 tracking-tighter">{{ $stat['subtitle'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Patient Queue -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                    <div>
                        <h3 class="text-sm font-black text-brand-teal uppercase tracking-widest leading-none">Live Session Queue</h3>
                        <p class="text-xl font-bold text-slate-900 dark:text-white mt-2 leading-none">Consultation Flow</p>
                    </div>
                    <span class="px-4 py-2 bg-brand-teal/10 text-brand-teal rounded-xl text-[10px] font-black uppercase tracking-widest border border-brand-teal/20">{{ now()->format('M d, Y') }}</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-400 text-[10px] font-black uppercase tracking-widest">
                                <th class="px-8 py-4">Session Time</th>
                                <th class="px-8 py-4">Patient Identity</th>
                                <th class="px-8 py-4">Status</th>
                                <th class="px-8 py-4 text-right">Access</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($todayAppointments as $appointment)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/20 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="text-slate-900 dark:text-white font-black text-base">{{ $appointment->start_time->format('h:i A') }}</div>
                                        <div class="text-slate-400 text-[10px] font-bold uppercase mt-1">{{ $appointment->start_time->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-slate-900 dark:text-white font-bold leading-tight">{{ $appointment->patient->full_name }}</div>
                                        <div class="text-brand-teal text-[10px] font-black uppercase mt-1 tracking-tighter">{{ $appointment->type ?? 'General Checkup' }} • {{ $appointment->patient->gender }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400',
                                                'checked_in' => 'bg-amber-100 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400',
                                                'in_progress' => 'bg-brand-teal/10 text-brand-teal border-brand-teal/20',
                                                'completed' => 'bg-brand-green/10 text-brand-green border-brand-green/20',
                                            ];
                                            $color = $statusColors[$appointment->status] ?? 'bg-slate-100 text-slate-400';
                                        @endphp
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-transparent {{ $color }}">
                                            {{ str_replace('_', ' ', $appointment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($appointment->status !== 'completed')
                                            <button wire:click="startConsultation({{ $appointment->id }})" class="px-5 py-2.5 bg-brand-teal text-white text-[10px] font-black uppercase rounded-xl transition-all shadow-md shadow-brand-teal/10 hover:scale-105">
                                                {{ $appointment->status === 'in_progress' ? 'Resume' : 'Call Patient' }}
                                            </button>
                                        @else
                                            <button class="p-2.5 text-slate-300 hover:text-brand-teal transition-all">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100 dark:border-slate-700 shadow-sm">
                                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Master schedule clear for today</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Supportive Intelligence -->
        <div class="space-y-6">
            <!-- Weekly Chart -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6 leading-none">Patient Volume Trend</h3>
                <div class="flex items-end justify-between gap-3 h-40 pt-4">
                    @foreach($weeklyStats as $stat)
                        <div class="flex-1 flex flex-col items-center gap-3 group">
                            <div class="w-full relative h-32 flex items-end">
                                <div class="w-full bg-brand-teal/10 rounded-t-lg transition-all group-hover:bg-brand-teal/30 group-hover:h-full border-b-2 border-brand-teal" 
                                     style="height: {{ max(($stat['count'] / max($weeklyStats->pluck('count')->max(), 1)) * 100, 10) }}%">
                                </div>
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-tighter text-slate-400 group-hover:text-brand-teal transition-colors">{{ $stat['day'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Clinical Files -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm">
                <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-6 leading-none">New Case Boardings</h3>
                <div class="space-y-4">
                    @forelse($recentActivity as $activity)
                        <div class="flex items-center gap-4 p-4 bg-slate-50 dark:bg-slate-800/30 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-brand-teal/30 transition-all group">
                            <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-teal font-black text-sm">
                                {{ substr($activity->patient->full_name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-brand-teal transition-colors">{{ $activity->patient->full_name }}</h4>
                                <p class="text-[9px] text-slate-400 font-black uppercase mt-1 truncate">{{ $activity->medicalRecord?->diagnosis ?? 'Routine Inspection' }}</p>
                            </div>
                            <span class="text-[9px] font-black text-slate-300 uppercase shrink-0">{{ $activity->appointment_date->diffForHumans(null, true) }}</span>
                        </div>
                    @empty
                        <p class="text-center py-6 text-slate-400 text-[10px] font-black uppercase tracking-widest italic opacity-50">No recent closed cases</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>