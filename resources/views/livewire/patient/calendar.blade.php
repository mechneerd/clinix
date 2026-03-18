<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-brand-teal/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                    <span class="text-brand-teal">Patient Portal</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    <span>Medical Calendar</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none">Your <span class="text-brand-teal">Schedule</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-3 font-medium text-sm">Track your visits and set vital reminders.</p>
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
                <div class="min-h-[140px] p-4 border-r border-b border-slate-100 dark:border-slate-800 last:border-r-0 {{ !$day['isCurrentMonth'] ? 'bg-slate-50/50 dark:bg-slate-800/30' : '' }}">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-black {{ $day['fullDate'] == now()->toDateString() ? 'w-8 h-8 bg-brand-teal text-white rounded-lg flex items-center justify-center' : ($day['isCurrentMonth'] ? 'text-slate-900 dark:text-white' : 'text-slate-300') }}">
                            {{ $day['day'] }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        @if(isset($appointments[$day['fullDate']]))
                            @foreach($appointments[$day['fullDate']] as $app)
                                <div class="p-2 rounded-xl border {{ $app['status'] == 'confirmed' ? 'bg-brand-teal/5 border-brand-teal/20' : 'bg-slate-50 border-slate-200 dark:bg-slate-800 dark:border-slate-700' }} group cursor-pointer relative">
                                    <div class="flex flex-col gap-1">
                                        <p class="text-[10px] font-black text-slate-900 dark:text-white truncate">Dr. {{ $app['doctor']['user']['name'] }}</p>
                                        <div class="flex items-center justify-between">
                                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tighter">
                                                {{ \Carbon\Carbon::parse($app['start_time'])->format('H:i') }}
                                            </span>
                                            @if($app['reminder_minutes'])
                                                <div class="w-1.5 h-1.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]" title="Reminder set"></div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Quick Actions / Hover Info -->
                                    <div class="absolute inset-0 bg-white/95 dark:bg-slate-900/95 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 rounded-xl border border-brand-teal/30 z-10 shadow-xl backdrop-blur-sm">
                                        <button wire:click="setReminder({{ $app['id'] }}, 60)" class="p-1 hover:text-brand-teal transition-colors" title="1h Reminder">
                                            <x-icons name="clock" class="w-4 h-4" />
                                        </button>
                                        <button wire:click="setReminder({{ $app['id'] }}, 1440)" class="p-1 hover:text-brand-teal transition-colors" title="1d Reminder">
                                            <x-icons name="calendar" class="w-4 h-4" />
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Info Panel -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-amber-50 dark:bg-amber-500/5 border border-amber-100 dark:border-amber-500/10 rounded-3xl p-6 flex items-center gap-6">
            <div class="w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center shrink-0">
                <x-icons name="bell" class="w-6 h-6 text-amber-600 dark:text-amber-500" />
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Active Reminders</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Appointments with an amber indicator have active alerts set. You will receive notifications at the specified interval.</p>
            </div>
        </div>

        <div class="bg-brand-teal/5 border border-brand-teal/10 rounded-3xl p-6 flex items-center gap-6">
            <div class="w-12 h-12 rounded-xl bg-brand-teal/10 flex items-center justify-center shrink-0">
                <x-icons name="hospital" class="w-6 h-6 text-brand-teal" />
            </div>
            <div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white">Clinical Verification</h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 leading-relaxed">Only <span class="text-brand-teal font-black">Confirmed</span> sessions can have reminders set to ensure schedule accuracy.</p>
            </div>
        </div>
    </div>
</div>
