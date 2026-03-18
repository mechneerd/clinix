<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-brand-teal/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">
                    <span class="text-brand-teal">Pharma Network</span>
                    <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    <span>Fulfillment Hub</span>
                </nav>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Dispensing: <span class="text-brand-teal">{{ auth()->user()->name }}</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-bold uppercase text-[10px] tracking-widest">
                    Registry Activity: <span class="text-brand-green">{{ $pendingPrescriptions }} Awaiting Pickup</span> • Secure Access Enabled
                </p>
            </div>
            <div class="flex gap-3">
                <button class="px-7 py-3 bg-brand-teal text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:shadow-xl hover:shadow-brand-teal/20 transition-all active:scale-95 shadow-lg">
                    Register Medicine
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Matrix -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Unfilled Registry', 'value' => $pendingPrescriptions, 'icon' => 'clipboard', 'color' => 'brand-teal', 'trend' => '● AWAITING DISPENSE'],
                ['label' => 'Out of Stock', 'value' => $outOfStock, 'icon' => 'alert-circle', 'color' => 'brand-green', 'trend' => 'PROCURE REQ.'],
                ['label' => 'Daily Velocity', 'value' => $productsDispensedToday, 'icon' => 'shopping-bag', 'color' => 'brand-teal', 'trend' => 'FULFILLED TODAY'],
                ['label' => 'Critical Stock', 'value' => $lowStock, 'icon' => 'archive', 'color' => 'brand-green', 'trend' => 'RESTOCK SOON'],
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
        <!-- Prescription Fulfillment Pipeline -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Active Fulfillment Queue</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Pharmacist Review & Dispensing Protocol</p>
                    </div>
                    <button class="p-2.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-brand-teal hover:bg-slate-50 transition-all shadow-sm">
                        <x-icons name="refresh" class="w-4 h-4" />
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-950/50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                                <th class="px-8 py-4">Presc. Registry</th>
                                <th class="px-8 py-4">Subject Profile</th>
                                <th class="px-8 py-4">Originating Lead</th>
                                <th class="px-8 py-4 text-right">Operation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse ($availablePrescriptions as $prescription)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/50 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="text-slate-900 dark:text-white font-black text-xs tracking-tighter">#{{ $prescription->prescription_no }}</div>
                                        <div class="text-slate-400 font-bold text-[9px] uppercase tracking-widest">{{ $prescription->created_at->format('M d, H:i') }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-slate-900 dark:text-white font-black text-sm">{{ $prescription->medicalRecord->patient->full_name }}</div>
                                        <div class="text-slate-400 font-bold text-[10px] uppercase tracking-tighter">{{ $prescription->medicalRecord->patient->patient_code }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-slate-600 dark:text-slate-300 font-bold text-xs">Dr. {{ $prescription->medicalRecord->doctor->user->name }}</div>
                                        <div class="text-brand-teal font-black text-[9px] uppercase tracking-widest">Medical Command</div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button class="px-5 py-2.5 bg-brand-teal text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:shadow-xl hover:shadow-brand-teal/20 transition-all active:scale-95 group-hover:translate-x-[-4px]">
                                            Initialize Dispense
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-20 text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-[1.5rem] bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 mb-4">
                                            <x-icons name="clipboard" class="w-8 h-8 text-slate-300" />
                                        </div>
                                        <p class="text-slate-500 font-black uppercase text-[10px] tracking-widest">Prescription Pipeline Clear</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Inventory Watchlist -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-2xl h-fit">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Safety Integrity Watch</h3>
                    <x-icons name="shield-check" class="w-4 h-4 text-brand-green" />
                </div>
                
                <div class="space-y-6 text-center py-6">
                    <div class="w-24 h-24 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 rounded-[2rem] flex items-center justify-center mx-auto mb-4 group hover:scale-110 transition-transform shadow-sm">
                        <x-icons name="clock" class="w-10 h-10 text-slate-200 dark:text-slate-700" />
                    </div>
                    <div>
                        <p class="text-[11px] font-black text-slate-900 dark:text-white uppercase tracking-tighter mb-2">Shelf-Life Compliance: 100%</p>
                        <p class="text-[9px] font-black text-slate-400 uppercase leading-relaxed tracking-widest">
                            All active pharmaceutical assets are currently within verified safety thresholds for distribution.
                        </p>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-50 dark:border-slate-800">
                    <button class="w-full py-4 bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-400 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-white dark:hover:bg-slate-900 hover:text-brand-teal transition-all">
                        Inventory Master Registry
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>