<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $clinic->name }}</h1>
            <p class="text-slate-500 dark:text-slate-400 text-sm">Clinic Management & Operations Hub</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('clinic.appointments.create') }}" wire:navigate class="px-5 py-2.5 bg-brand-teal text-white rounded-xl font-bold shadow-lg shadow-brand-teal/20 hover:scale-[1.02] transition-all text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Appointment
            </a>
            <a href="{{ route('clinic.patients.create') }}" wire:navigate class="px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-white rounded-xl font-bold border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all text-sm shadow-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                Add Patient
            </a>
        </div>
    </div>

    <!-- Quick Insights -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach([
            ['key' => 'total_patients', 'label' => 'Total Patients', 'icon' => 'patient', 'color' => 'brand-teal'],
            ['key' => 'today_appointments', 'label' => "Today's Appts", 'icon' => 'calendar', 'color' => 'brand-teal'],
            ['key' => 'pending_appointments', 'label' => 'Pending', 'icon' => 'clock', 'color' => 'amber'],
            ['key' => 'checked_in', 'label' => 'In Service', 'icon' => 'check', 'color' => 'brand-green'],
            ['key' => 'total_staff', 'label' => 'Total Staff', 'icon' => 'users', 'color' => 'brand-teal'],
            ['key' => 'monthly_revenue', 'label' => 'Revenue', 'icon' => 'dollar', 'color' => 'brand-green', 'prefix' => '$'],
        ] as $stat)
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl p-4 shadow-sm hover:border-brand-teal/50 transition-all group">
            <div class="w-10 h-10 rounded-xl bg-{{ $stat['color'] === 'amber' ? 'amber-100 dark:bg-amber-500/10' : ($stat['color'] === 'brand-green' ? 'emerald-100 dark:bg-emerald-500/10' : 'slate-100 dark:bg-slate-800') }} flex items-center justify-center mb-3">
                <x-icons :name="$stat['icon']" class="w-5 h-5 text-{{ $stat['color'] === 'amber' ? 'amber-600 dark:text-amber-400' : ($stat['color'] === 'brand-green' ? 'emerald-600 dark:text-emerald-400' : 'brand-teal') }}" />
            </div>
            <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">{{ ($stat['prefix'] ?? '') . number_format($stats[$stat['key']]) }}</h3>
            <p class="text-[10px] uppercase font-bold text-slate-400 mt-1 tracking-wider">{{ $stat['label'] }}</p>
        </div>
        @endforeach
    </div>

    <!-- Main Operational Grid -->
    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- Live Queue / Today's Appointments -->
        <div class="lg:col-span-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                <div>
                    <h3 class="text-sm font-black text-brand-teal uppercase tracking-widest leading-none">Live Queue</h3>
                    <p class="text-lg font-bold text-slate-900 dark:text-white mt-1 leading-none">Today's Patient Flow</p>
                </div>
                <a href="{{ route('clinic.appointments') }}" wire:navigate class="px-4 py-2 text-xs font-bold text-brand-teal hover:bg-brand-teal/5 rounded-lg transition-all uppercase">View Master Schedule</a>
            </div>
            
            <div class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($todayAppointments as $appointment)
                <div class="p-4 flex items-center gap-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-brand-teal font-black text-lg shadow-sm">
                        {{ substr($appointment->patient->first_name, 0, 1) }}{{ substr($appointment->patient->last_name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-900 dark:text-white text-base leading-tight">{{ $appointment->patient->full_name }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mt-1">
                            With <span class="text-brand-teal">Dr. {{ $appointment->doctor->user->name }}</span> • {{ $appointment->start_time->format('h:i A') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($appointment->status === 'scheduled')
                        <button wire:click="checkIn({{ $appointment->id }})" class="px-3 py-1.5 bg-brand-teal text-white text-[10px] font-bold uppercase rounded-lg hover:bg-brand-teal-dark transition-all shadow-sm">Check In</button>
                        @elseif($appointment->status === 'checked_in')
                        <span class="px-3 py-1.5 bg-brand-green/10 text-brand-green text-[10px] font-bold uppercase rounded-lg border border-brand-green/20">Checked In</span>
                        @else
                        <span class="px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 text-[10px] font-bold uppercase rounded-lg border border-slate-200 dark:border-slate-700">{{ $appointment->status }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-20 text-center">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-200 dark:border-slate-700 shadow-sm">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <p class="text-slate-900 dark:text-white font-bold">Schedule is Empty</p>
                    <p class="text-slate-500 text-xs mt-1 uppercase tracking-tighter font-bold">No clinical sessions tracked today</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Support Widgets -->
        <div class="space-y-6">
            <!-- Staffing Distribution -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 dark:text-white mb-6 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-6 bg-brand-teal rounded-full"></span>
                    Workforce
                </h3>
                <div class="space-y-5">
                    @foreach($staffStats as $role => $count)
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-[11px] font-black uppercase tracking-tighter">
                            <span class="text-slate-500">{{ str_replace('_', ' ', $role) }}</span>
                            <span class="text-brand-teal">{{ $count }}</span>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-brand-teal rounded-full" style="width: {{ min($count * 10, 100) }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Recently Boarded Patients -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 dark:text-white mb-6 uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2 h-6 bg-brand-green rounded-full"></span>
                    New Arrivals
                </h3>
                <div class="space-y-4">
                    @forelse($recentPatients as $patient)
                    <div class="flex items-center gap-3 group border-b border-slate-50 dark:border-slate-800 pb-3 last:border-0 last:pb-0">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-brand-green font-black text-sm border border-slate-200 dark:border-slate-700 shadow-sm group-hover:border-brand-green/30 transition-all">
                            {{ substr($patient->first_name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate group-hover:text-brand-green transition-colors">{{ $patient->full_name }}</p>
                            <p class="text-[10px] font-bold uppercase tracking-tight text-slate-400 mt-1">{{ $patient->patient_code }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 group-hover:text-brand-green transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                    @empty
                    <p class="text-center py-4 text-slate-400 text-xs font-bold uppercase">No recent enrollments</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>