<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-brand-teal/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">
                    <span class="text-brand-teal">Clinical Ops</span>
                    <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    <span>Nursing Hub</span>
                </nav>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Active Duty: <span class="text-brand-teal">Nurse {{ auth()->user()->name }}</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-bold uppercase text-[10px] tracking-widest">
                    Operational Status: <span class="text-brand-green">Primary Response</span> • Queue: {{ $waitingPatients }} Active
                </p>
            </div>
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-2xl flex items-center gap-2">
                    <div class="w-2 h-2 bg-brand-teal rounded-full animate-pulse"></div>
                    <span class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Station Alpha</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Awaiting Vitals', 'value' => $waitingPatients, 'icon' => 'activity', 'color' => 'brand-teal', 'trend' => '● ACTIVE QUEUE'],
                ['label' => 'Triage Complete', 'value' => $completedToday, 'icon' => 'check-circle', 'color' => 'brand-green', 'trend' => 'TODAY\'S TOTAL'],
                ['label' => 'Daily Registry', 'value' => $totalAppointments, 'icon' => 'users', 'color' => 'brand-teal', 'trend' => 'FULL LOG'],
                ['label' => 'Station Tasks', 'value' => '12', 'icon' => 'clipboard', 'color' => 'brand-green', 'trend' => 'PENDING ACTION'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2rem] p-6 hover:shadow-xl hover:shadow-{{ $stat['color'] }}/5 transition-all group relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-{{ $stat['color'] }}/5 rounded-full blur-2xl group-hover:bg-{{ $stat['color'] }}/10 transition-colors"></div>
            
            <div class="w-12 h-12 rounded-2xl bg-{{ $stat['color'] }}/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform relative z-10">
                <x-icons :name="$stat['icon']" class="w-6 h-6 text-{{ $stat['color'] }}" />
            </div>
            
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1 relative z-10">{{ $stat['label'] }}</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white relative z-10">{{ $stat['value'] }}</h3>
            <p class="text-[9px] font-black text-{{ $stat['color'] }} mt-2 tracking-tighter relative z-10 italic">{{ $stat['trend'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Vitals Monitoring Queue -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Active Triage Queue</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Vitals Assessment Protocol Required</p>
                    </div>
                    <button class="p-2.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-brand-teal hover:bg-slate-50 transition-all shadow-sm">
                        <x-icons name="refresh" class="w-4 h-4" />
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-950/50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                                <th class="px-8 py-4">Patient Profile</th>
                                <th class="px-8 py-4">Dest. Provider</th>
                                <th class="px-8 py-4">Queue State</th>
                                <th class="px-8 py-4 text-right">Protocol</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($upcomingAppointments as $appointment)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/50 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center font-black text-brand-teal text-xs">
                                                {{ substr($appointment->patient->full_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-slate-900 dark:text-white font-black text-sm">{{ $appointment->patient->full_name }}</div>
                                                <div class="text-slate-400 font-bold text-[10px] uppercase tracking-tighter">{{ $appointment->patient->patient_code }} • {{ $appointment->patient->gender }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-slate-600 dark:text-slate-300 font-bold text-xs">Dr. {{ $appointment->doctor->user->name }}</div>
                                        <div class="text-brand-teal font-black text-[9px] uppercase tracking-widest">{{ $appointment->doctor->department->name ?? 'OPD Station' }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @php
                                            $statusColors = [
                                                'scheduled' => 'bg-slate-100 text-slate-400 dark:bg-slate-800',
                                                'confirmed' => 'bg-brand-teal/10 text-brand-teal',
                                                'checked_in' => 'bg-brand-green/10 text-brand-green',
                                            ];
                                            $color = $statusColors[$appointment->status] ?? 'bg-slate-100 text-slate-400';
                                        @endphp
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-[0.1em] {{ $color }} border border-current opacity-80">
                                            {{ str_replace('_', ' ', $appointment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button class="px-5 py-2.5 bg-brand-teal text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:shadow-lg hover:shadow-brand-teal/20 transition-all active:scale-95 group-hover:translate-x-[-4px]">
                                            Initialize Vitals
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-[1.5rem] bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 mb-4">
                                            <x-icons name="users" class="w-8 h-8 text-slate-300" />
                                        </div>
                                        <p class="text-slate-500 font-black uppercase text-[10px] tracking-widest">Station Queue Empty</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Flow Analysis -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-2xl h-fit">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Patient Admission Velocity</h3>
                    <x-icons name="trending-up" class="w-4 h-4 text-brand-green" />
                </div>
                
                <div class="flex items-end justify-between gap-3 h-48 pt-4">
                    @foreach($weeklyStats as $stat)
                        <div class="flex-1 flex flex-col items-center gap-4 group">
                            <div class="w-full relative h-40 flex items-end">
                                <div class="w-full bg-slate-100 dark:bg-slate-800/50 rounded-t-2xl transition-all duration-500 group-hover:bg-brand-teal group-hover:shadow-lg group-hover:shadow-brand-teal/20" 
                                     style="height: {{ max(($stat['count'] / max($weeklyStats->pluck('count')->max(), 1)) * 100, 10) }}%">
                                    
                                    <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[9px] font-black px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                        {{ $stat['count'] }}
                                    </div>
                                </div>
                            </div>
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 group-hover:text-brand-teal transition-colors">{{ $stat['day'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 pt-8 border-t border-slate-50 dark:border-slate-800">
                    <div class="flex items-center justify-between group cursor-pointer">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Efficiency Rating</p>
                            <p class="text-xl font-black text-brand-teal">8.4<span class="text-xs text-slate-400 ml-1">/10.0</span></p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-brand-teal/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <x-icons name="award" class="w-6 h-6 text-brand-teal" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>