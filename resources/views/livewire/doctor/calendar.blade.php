<div class="space-y-8 pb-20" x-data="{ role: 'doctor' }" wire:poll.15s="loadAppointments">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-brand-teal/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                    <span class="text-brand-teal">Doctor Portal</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    <span>Schedule Analytics</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none">Your <span class="text-brand-teal">Caseload</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-3 font-medium text-sm">Monitor appointment density and optimized daily flow.</p>
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
                <div class="min-h-[120px] p-4 border-r border-b border-slate-100 dark:border-slate-800 last:border-r-0 {{ !$day['isCurrentMonth'] ? 'bg-slate-50/50 dark:bg-slate-800/30' : '' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-black {{ $day['fullDate'] == now()->toDateString() ? 'w-8 h-8 bg-brand-teal text-white rounded-lg flex items-center justify-center' : ($day['isCurrentMonth'] ? 'text-slate-900 dark:text-white' : 'text-slate-300') }}">
                            {{ $day['day'] }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center justify-center h-full pb-4">
                        @if(isset($appointments[$day['fullDate']]))
                            <div class="relative group">
                                <div class="w-12 h-12 bg-brand-teal/10 rounded-2xl flex items-center justify-center border border-brand-teal/20 group-hover:scale-110 transition-transform">
                                    <span class="text-lg font-black text-brand-teal">{{ $appointments[$day['fullDate']] }}</span>
                                </div>
                                <div class="absolute -top-1 -right-1 w-3 h-3 bg-brand-teal rounded-full border-2 border-white dark:border-slate-900"></div>
                            </div>
                            <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest mt-2">Booked</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Health/Insight Panel -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 bg-brand-teal rounded-[2.5rem] p-8 text-white relative overflow-hidden flex items-center justify-between">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.1),transparent)]"></div>
            <div class="relative z-10 space-y-2">
                <h4 class="text-xl font-black">Caseload Optimization</h4>
                <p class="text-brand-teal-light text-sm opacity-90 font-medium max-w-md">Your calendar currently shows appointments scheduled across your practice. High density days are highlighted with caseload indicators.</p>
            </div>
            <div class="relative z-10 hidden xl:block">
                <div class="w-24 h-24 rounded-full border-8 border-white/20 flex items-center justify-center">
                    <span class="text-2xl font-black">78%</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm flex flex-col items-center justify-center text-center">
            <div class="w-12 h-12 bg-brand-green/10 rounded-xl flex items-center justify-center mb-4">
                <x-icons name="calendar" class="w-6 h-6 text-brand-green" />
            </div>
            <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Sync Enabled</h4>
            <p class="text-xs text-slate-400 font-bold mt-2">Real-time updates across all clinic departments.</p>
        </div>
    </div>
</div>
