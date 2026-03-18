<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-brand-teal/5 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                    <span class="text-brand-teal">Patient Portal</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    <span>Overview</span>
                </nav>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight leading-none">Hello, <span class="text-brand-teal">{{ explode(' ', auth()->user()->name)[0] }}</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-3 font-medium text-sm">Your health journey, unified and secure.</p>
            </div>
            <div class="flex gap-3">
                @if($patient && $patient->clinics->count() > 0)
                <button wire:click="startChatWithClinic({{ $patient->clinics->first()->id }})" class="px-6 py-4 bg-white dark:bg-slate-800 text-brand-teal dark:text-white rounded-2xl font-bold border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all flex items-center gap-2 shadow-sm">
                    <x-icons name="message" class="w-5 h-5" />
                    Chat with Clinic
                </button>
                @endif
                <a href="{{ route('patient.browse-clinics') }}" wire:navigate class="px-6 py-4 bg-brand-teal text-white rounded-2xl font-bold shadow-lg shadow-brand-teal/20 hover:scale-[1.02] transition-all flex items-center gap-2">
                    <x-icons name="plus" class="w-5 h-5" />
                    Book Consultation
                </a>
                <a href="{{ route('patient.calendar') }}" wire:navigate class="px-6 py-4 bg-slate-900 text-white rounded-2xl font-bold shadow-lg shadow-brand-teal/20 hover:scale-[1.02] transition-all flex items-center gap-2">
                    <x-icons name="calendar" class="w-5 h-5" />
                    My Calendar
                </a>
            </div>
        </div>
    </div>

    @if(!$patient)
        <div class="bg-amber-50 dark:bg-amber-500/10 border border-amber-100 dark:border-amber-500/20 rounded-3xl p-6 flex items-center gap-6 shadow-sm">
            <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-500/20 flex items-center justify-center shrink-0">
                <x-icons name="user-alert" class="w-7 h-7 text-amber-600 dark:text-amber-500" />
            </div>
            <div class="flex-1">
                <h3 class="text-slate-900 dark:text-white font-bold text-base">Complete Your Identity</h3>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-medium">Please provide your medical details to unlock the full clinical experience.</p>
            </div>
            <button class="px-5 py-2.5 bg-white dark:bg-slate-800 text-slate-700 dark:text-white rounded-xl font-bold border border-slate-200 dark:border-slate-700 hover:bg-slate-50 transition-all text-xs">
                Update Now
            </button>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Upcoming Appointments -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-sm">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-black text-brand-teal uppercase tracking-widest">Medical Calendar</h3>
                        <p class="text-xl font-bold text-slate-900 dark:text-white mt-1">Scheduled Visits</p>
                    </div>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($upcomingAppointments as $appointment)
                        <div class="p-6 md:p-8 hover:bg-slate-50 dark:hover:bg-slate-800/10 transition-colors group flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex flex-col items-center justify-center shrink-0 shadow-inner group-hover:border-brand-teal/30 transition-all">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $appointment->appointment_date->format('M') }}</span>
                                    <span class="text-2xl font-black text-slate-900 dark:text-white leading-none">{{ $appointment->appointment_date->format('d') }}</span>
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-slate-900 dark:text-white group-hover:text-brand-teal transition-colors">Dr. {{ $appointment->doctor->user->name }}</h4>
                                    <div class="text-slate-500 text-xs font-bold uppercase tracking-wide flex items-center gap-1.5 mt-1">
                                        <x-icons name="hospital" class="w-3.5 h-3.5 text-brand-teal" />
                                        {{ $appointment->clinic->name }}
                                    </div>
                                    <div class="text-[11px] font-black text-slate-400 mt-2 flex items-center gap-1.5 uppercase tracking-tighter">
                                        <x-icons name="clock" class="w-3.5 h-3.5" />
                                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('H:i') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                                        'confirmed' => 'bg-brand-teal/10 text-brand-teal border-brand-teal/20',
                                        'scheduled' => 'bg-brand-green/10 text-brand-green border-brand-green/20',
                                        'checked_in' => 'bg-brand-green/10 text-brand-green border-brand-green/20',
                                    ];
                                    $color = $statusColors[$appointment->status] ?? 'bg-slate-100 text-slate-500 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700';
                                @endphp
                                <span class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border {{ $color }}">
                                    {{ str_replace('_', ' ', $appointment->status) }}
                                </span>
                                
                                @if(in_array($appointment->status, ['confirmed', 'scheduled']))
                                    <div x-data="{ open: false }" class="relative">
                                        <button @click="open = !open" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors text-slate-400 hover:text-brand-teal">
                                            <x-icons name="bell" class="w-4 h-4 {{ $appointment->reminder_minutes ? 'text-amber-500' : '' }}" />
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl z-20 py-2">
                                            <p class="px-4 py-2 text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100 dark:border-slate-700 mb-1">Set Reminder</p>
                                            <button @click="open = false; $wire.setReminder({{ $appointment->id }}, 60)" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center justify-between">
                                                1 Hour Before
                                                @if($appointment->reminder_minutes == 60) <span class="w-2 h-2 rounded-full bg-brand-teal"></span> @endif
                                            </button>
                                            <button @click="open = false; $wire.setReminder({{ $appointment->id }}, 300)" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center justify-between">
                                                5 Hours Before
                                                @if($appointment->reminder_minutes == 300) <span class="w-2 h-2 rounded-full bg-brand-teal"></span> @endif
                                            </button>
                                            <button @click="open = false; $wire.setReminder({{ $appointment->id }}, 1440)" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 flex items-center justify-between">
                                                1 Day Before
                                                @if($appointment->reminder_minutes == 1440) <span class="w-2 h-2 rounded-full bg-brand-teal"></span> @endif
                                            </button>
                                            <button @click="open = false; $wire.setReminder({{ $appointment->id }}, null)" class="w-full text-left px-4 py-2 text-xs font-bold hover:bg-slate-50 dark:hover:bg-slate-700 text-rose-500">
                                                Turn Off
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                @if($appointment->status === 'pending')
                                    <span class="text-[10px] text-slate-400 font-bold italic max-w-[120px] leading-tight text-right">Awaiting Provider<br>Audit</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-16 text-center">
                            <div class="w-20 h-20 bg-slate-50 dark:bg-slate-800 rounded-3xl flex items-center justify-center mx-auto mb-6 border border-slate-100 dark:border-slate-700 shadow-sm">
                                <x-icons name="calendar" class="w-10 h-10 text-slate-300" />
                            </div>
                            <h4 class="text-slate-900 dark:text-white font-black text-lg">No Appointments Tracked</h4>
                            <p class="text-slate-500 text-sm font-medium max-w-xs mx-auto mt-2">Book your first session and keep track of your clinical journey.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Utility Sidebar -->
        <div class="space-y-6">
            <!-- Health Snapshot -->
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-sm">
                <h3 class="text-sm font-black text-slate-900 dark:text-white mb-6 uppercase tracking-wider">Health Snapshot</h3>
                <div class="space-y-4">
                    <div class="p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-brand-teal/30 transition-all cursor-pointer group shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-brand-teal/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <x-icons name="prescription" class="w-5 h-5 text-brand-teal" />
                            </div>
                            <div>
                                <p class="text-slate-900 dark:text-white font-black text-sm">Prescriptions</p>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter mt-1">Active Medications</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-brand-green/30 transition-all cursor-pointer group shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-brand-green/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <x-icons name="file-text" class="w-5 h-5 text-brand-green" />
                            </div>
                            <div>
                                <p class="text-slate-900 dark:text-white font-black text-sm">Lab Results</p>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter mt-1">Diagnostic Reports</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800 hover:border-brand-teal/30 transition-all cursor-pointer group shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-brand-teal/10 flex items-center justify-center group-hover:scale-110 transition-transform">
                                <x-icons name="dollar" class="w-5 h-5 text-brand-teal" />
                            </div>
                            <div>
                                <p class="text-slate-900 dark:text-white font-black text-sm">Medical Wallet</p>
                                <p class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter mt-1">Invoices & Bio Bills</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promotion / Engagement -->
            <div class="bg-brand-teal rounded-[2.5rem] p-8 text-white shadow-xl relative overflow-hidden text-center">
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <h4 class="text-lg font-black mb-3">Health on the Go</h4>
                    <p class="text-brand-teal-light dark:text-blue-100 text-[11px] font-bold leading-relaxed opacity-90">Consult with specialists via video call directly from your verified dashboard. Reassurance is just a click away.</p>
                    <button class="mt-6 w-full py-3 bg-white text-brand-teal rounded-xl font-black text-xs uppercase tracking-widest shadow-lg hover:shadow-brand-teal-dark/20 transition-all">Explore Platform</button>
                </div>
            </div>
        </div>
    </div>
</div>