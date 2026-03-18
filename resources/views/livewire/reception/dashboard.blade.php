<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-brand-teal/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">
                    <span class="text-brand-teal">Patient Logistics</span>
                    <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    <span>Reception Hub</span>
                </nav>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Services: <span class="text-brand-teal">{{ auth()->user()->name }}</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-bold uppercase text-[10px] tracking-widest">
                    Registry Traffic: <span class="text-brand-green">{{ $totalToday }} Appointments Scheduled</span> • Front Desk Active
                </p>
            </div>
            <div class="flex gap-3">
                <button class="px-7 py-3 bg-brand-teal text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:shadow-xl hover:shadow-brand-teal/20 transition-all active:scale-95 shadow-lg">
                    Initialize New Patient
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Daily Traffic', 'value' => $totalToday, 'icon' => 'calendar', 'color' => 'brand-teal', 'trend' => '● TOTAL VISITS'],
                ['label' => 'Facility Presence', 'value' => $checkedIn, 'icon' => 'check-circle', 'color' => 'brand-green', 'trend' => 'IN-FACILITY'],
                ['label' => 'Unpaid Registry', 'value' => $pendingInvoices, 'icon' => 'file-text', 'color' => 'brand-teal', 'trend' => 'ACTION REQ.'],
                ['label' => 'New Registries', 'value' => $walkIns, 'icon' => 'users', 'color' => 'brand-green', 'trend' => 'WALK-INS TODAY'],
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

    <div class="grid grid-cols-1 space-y-8">
        <!-- New Requests Alert Banner -->
        @if(count($pendingRequests) > 0)
        <div class="bg-brand-teal/10 border border-brand-teal/20 rounded-[2.5rem] p-8 shadow-xl relative overflow-hidden group">
            <div class="absolute right-0 top-0 w-64 h-64 bg-brand-teal/5 rounded-full blur-3xl -z-10 group-hover:bg-brand-teal/10 transition-all"></div>
            <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="flex items-center gap-6 text-center md:text-left">
                    <div class="w-16 h-16 rounded-[1.5rem] bg-brand-teal text-white flex items-center justify-center shadow-lg shadow-brand-teal/20">
                        <x-icons name="bell" class="w-8 h-8 animate-bounce-slow" />
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Critical Attention: Portal Requests</h3>
                        <p class="text-[10px] font-black text-brand-teal uppercase tracking-widest mt-1">
                            {{ count($pendingRequests) }} Identities Awaiting Protocol Verification
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-8 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white/50 dark:bg-slate-950/30 text-slate-400 text-[10px] uppercase font-black tracking-widest border-b border-brand-teal/10">
                            <th class="px-6 py-4">Registry Date</th>
                            <th class="px-6 py-4">Subject Profile</th>
                            <th class="px-6 py-4">Requested Authority</th>
                            <th class="px-6 py-4 text-right">Verification</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-brand-teal/5">
                        @foreach($pendingRequests as $request)
                            <tr class="hover:bg-brand-teal/5 transition-colors group">
                                <td class="px-6 py-5">
                                    <div class="text-slate-900 dark:text-white font-black text-xs">{{ $request->appointment_date->format('d M, Y') }}</div>
                                    <div class="text-brand-teal font-bold text-[9px] uppercase tracking-widest">{{ \Carbon\Carbon::parse($request->start_time)->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-slate-900 dark:text-white font-black text-sm">{{ $request->patient->full_name }}</div>
                                    <div class="text-slate-400 font-bold text-[10px]">{{ $request->patient->phone }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-slate-600 dark:text-slate-300 font-bold text-xs">Dr. {{ $request->doctor->user->name }}</div>
                                    <div class="text-brand-teal/60 font-black text-[9px] uppercase tracking-widest">{{ $request->doctor->department->name ?? 'OPD Station' }}</div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="cancel({{ $request->id }})" class="p-2.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-400 hover:text-rose-500 rounded-xl transition-all shadow-sm">
                                            <x-icons name="x-circle" class="w-4 h-4" />
                                        </button>
                                        <button wire:click="confirm({{ $request->id }})" class="px-4 py-2 bg-brand-teal text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:shadow-lg transition-all animate-pulse hover:animate-none">
                                            Confirm Identity
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- General Traffic Queue -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Master Registry Queue</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Live Telemetry of Patient Admissions</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 dark:bg-slate-950/50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                            <th class="px-8 py-4">Registry Slot</th>
                            <th class="px-8 py-4">Subject Profile</th>
                            <th class="px-8 py-4">Requested Provider</th>
                            <th class="px-8 py-4">Protocol State</th>
                            <th class="px-8 py-4 text-right">Operation</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse ($appointmentQueue as $appointment)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/50 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="text-slate-900 dark:text-white font-black text-sm tracking-tighter">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</div>
                                    <div class="text-slate-400 font-bold text-[9px] uppercase tracking-widest">ETA Window</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-slate-900 dark:text-white font-black text-sm">{{ $appointment->patient->full_name }}</div>
                                    <div class="text-slate-400 font-bold text-[10px] uppercase tracking-tighter">{{ $appointment->patient->patient_code }}</div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-slate-600 dark:text-slate-300 font-bold text-xs uppercase italic">Dr. {{ $appointment->doctor->user->name }}</div>
                                    <div class="text-brand-teal font-black text-[9px] uppercase tracking-widest">Attending Unit</div>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $statusColors = [
                                            'scheduled' => 'bg-slate-100 text-slate-400 dark:bg-slate-800',
                                            'confirmed' => 'bg-brand-teal/10 text-brand-teal',
                                            'checked_in' => 'bg-brand-green/10 text-brand-green',
                                            'completed' => 'bg-slate-50 text-slate-300 line-through dark:bg-slate-900',
                                        ];
                                        $color = $statusColors[$appointment->status] ?? 'bg-slate-100 text-slate-400';
                                    @endphp
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-[0.1em] {{ $color }} border border-current opacity-80">
                                        {{ str_replace('_', ' ', $appointment->status) }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    @if($appointment->status === 'scheduled' || $appointment->status === 'confirmed')
                                        <button class="px-5 py-2.5 bg-brand-teal text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:shadow-xl hover:shadow-brand-teal/20 transition-all active:scale-95 group-hover:translate-x-[-4px]">
                                            Initialize Check-In
                                        </button>
                                    @else
                                        <div class="flex items-center justify-end gap-2 text-[10px] font-black text-slate-400 uppercase tracking-widest italic opacity-50">
                                            <x-icons name="check-circle" class="w-4 h-4 text-brand-green" />
                                            Active Session
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-[1.5rem] bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 mb-4">
                                        <x-icons name="calendar" class="w-8 h-8 text-slate-300" />
                                    </div>
                                    <p class="text-slate-500 font-black uppercase text-[10px] tracking-widest">No Registries Scheduled for Current Protocol</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>