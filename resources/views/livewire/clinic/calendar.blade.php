<div class="space-y-8 pb-20" wire:poll.15s="loadAppointments">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-brand-teal/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                    <span class="text-brand-teal">Clinic Admin</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    <span>Central Schedule</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none">Ecosystem <span class="text-brand-teal">Timeline</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-3 font-medium text-sm">Comprehensive view of all clinical sessions and provider distribution.</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="prevMonth" class="p-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-2xl border border-slate-200 dark:border-slate-700 hover:text-brand-teal transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>
                <div class="px-6 py-4 bg-white dark:bg-slate-800 text-slate-900 dark:text-white rounded-2xl font-bold border border-slate-200 dark:border-slate-700 shadow-sm flex items-center gap-3">
                    <x-icons name="calendar" class="w-5 h-5 text-brand-teal" />
                    {{ \Carbon\Carbon::create($currentYear, $currentMonth, 1)->format('F Y') }}
                </div>
                <button wire:click="nextMonth" class="p-4 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-400 rounded-2xl border border-slate-200 dark:border-slate-700 hover:text-brand-teal transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Calendar Grid -->
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
        <div class="grid grid-cols-7 border-b border-slate-100 dark:border-slate-800">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                <div class="py-4 text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">{{ $dayName }}</div>
            @endforeach
        </div>

        <div class="grid grid-cols-7">
            @foreach($daysInMonth as $day)
                <div class="min-h-[160px] p-4 border-r border-b border-slate-100 dark:border-slate-800 last:border-r-0 {{ !$day['isCurrentMonth'] ? 'bg-slate-50/50 dark:bg-slate-800/30' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-black {{ $day['fullDate'] == now()->toDateString() ? 'w-8 h-8 bg-brand-teal text-white rounded-lg flex items-center justify-center' : ($day['isCurrentMonth'] ? 'text-slate-900 dark:text-white' : 'text-slate-300') }}">
                            {{ $day['day'] }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        @if(isset($appointments[$day['fullDate']]))
                            @foreach($appointments[$day['fullDate']] as $app)
                                <div class="p-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-brand-teal/30 transition-all cursor-pointer group">
                                    <div class="flex flex-col gap-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[8px] font-black text-brand-teal uppercase tracking-widest">{{ \Carbon\Carbon::parse($app['start_time'])->format('H:i') }}</span>
                                            <div class="w-1.5 h-1.5 rounded-full {{ $app['status'] == 'confirmed' ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>
                                        </div>
                                        <p class="text-[9px] font-black text-slate-900 dark:text-white truncate">Dr. {{ $app['doctor']['user']['name'] }}</p>
                                        <p class="text-[8px] font-bold text-slate-400 truncate">{{ $app['patient']['first_name'] }} {{ $app['patient']['last_name'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Admin Tools Panel -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white flex items-center gap-8 border border-slate-800 shadow-xl">
            <div class="w-16 h-16 rounded-2xl bg-brand-teal/20 flex items-center justify-center shrink-0">
                <x-icons name="users" class="w-8 h-8 text-brand-teal" />
            </div>
            <div>
                <h4 class="text-lg font-black tracking-tight">Provider Distribution</h4>
                <p class="text-slate-400 text-xs font-bold mt-1 leading-relaxed">This view shows all appointments across your clinic. Use it to identify peak hours and optimize doctor scheduling.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm flex items-center gap-6">
            <div class="flex -space-x-3">
                @foreach(range(1, 3) as $i)
                <div class="w-10 h-10 rounded-full border-2 border-white dark:border-slate-900 bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    <x-icons name="user" class="w-5 h-5 text-slate-400" />
                </div>
                @endforeach
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Multi-Doctor Oversight</h4>
                <p class="text-xs text-slate-400 font-bold mt-1">Cross-reference clinical loads in real-time.</p>
            </div>
        </div>
    </div>
</div>
