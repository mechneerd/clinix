<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-brand-teal/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">
                    <span class="text-brand-teal">Clinical Labs</span>
                    <svg class="w-3 h-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                    <span>Diagnostics Hub</span>
                </nav>
                <h1 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight">Diagnostics: <span class="text-brand-teal">{{ auth()->user()->name }}</span></h1>
                <p class="text-slate-500 dark:text-slate-400 mt-2 font-bold uppercase text-[10px] tracking-widest">
                    Registry Activity: <span class="text-brand-green">{{ $pendingTests }} Samples Pending</span> • High Precision Mode
                </p>
            </div>
            <div class="flex gap-3">
                <button class="px-7 py-3 bg-brand-teal text-white rounded-2xl font-black uppercase tracking-widest text-[10px] hover:shadow-xl hover:shadow-brand-teal/20 transition-all active:scale-95 shadow-lg">
                    Initialize New Order
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Pending Analysis', 'value' => $pendingTests, 'icon' => 'flask', 'color' => 'brand-teal', 'trend' => '● IN-QUEUE'],
                ['label' => 'Critical Values', 'value' => $criticalResults, 'icon' => 'alert-triangle', 'color' => 'brand-green', 'trend' => 'IMMEDIATE ACTION'],
                ['label' => 'Daily Output', 'value' => $collectedToday, 'icon' => 'check-circle', 'color' => 'brand-teal', 'trend' => 'PROCESSED TODAY'],
                ['label' => 'Inventory Status', 'value' => $lowStockConsumables, 'icon' => 'archive', 'color' => 'brand-green', 'trend' => 'REORDER REQ.'],
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
        <!-- Main Order Queue -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] overflow-hidden shadow-2xl">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Active Diagnostic Pipeline</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Real-time Telemetry of Lab Requests</p>
                    </div>
                    <button class="p-2.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-xl text-brand-teal hover:bg-slate-50 transition-all shadow-sm">
                        <x-icons name="refresh" class="w-4 h-4" />
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-950/50 text-slate-400 text-[10px] uppercase font-black tracking-widest">
                                <th class="px-8 py-4">Protocol ID</th>
                                <th class="px-8 py-4">Subject Profile</th>
                                <th class="px-8 py-4">Medical Lead</th>
                                <th class="px-8 py-4">Registry State</th>
                                <th class="px-8 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($orderQueue as $order)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-950/50 transition-colors group">
                                    <td class="px-8 py-6">
                                        <div class="text-slate-900 dark:text-white font-black text-xs tracking-tighter">#{{ $order->order_no }}</div>
                                        <div class="text-slate-400 font-bold text-[9px] uppercase tracking-widest">{{ $order->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-slate-900 dark:text-white font-black text-sm">{{ $order->patient->full_name }}</div>
                                        <div class="text-slate-400 font-bold text-[10px] uppercase tracking-tighter">{{ $order->patient->patient_code }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-slate-600 dark:text-slate-300 font-bold text-xs">Dr. {{ $order->doctor->user->name }}</div>
                                        <div class="text-brand-teal font-black text-[9px] uppercase tracking-widest">Attending Physician</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        @php
                                            $statusColors = [
                                                'pending' => 'bg-slate-100 text-slate-400 dark:bg-slate-800',
                                                'in_progress' => 'bg-brand-teal/10 text-brand-teal',
                                                'completed' => 'bg-brand-green/10 text-brand-green',
                                            ];
                                            $color = $statusColors[$order->status] ?? 'bg-slate-100 text-slate-400';
                                        @endphp
                                        <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-[0.1em] {{ $color }} border border-current opacity-80">
                                            {{ str_replace('_', ' ', $order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <button class="px-5 py-2.5 bg-white dark:bg-slate-950 border border-slate-200 dark:border-slate-800 text-slate-900 dark:text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 transition-all active:scale-95 group-hover:translate-x-[-4px] shadow-sm">
                                            Process Result
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-[1.5rem] bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 mb-4">
                                            <x-icons name="flask" class="w-8 h-8 text-slate-300" />
                                        </div>
                                        <p class="text-slate-500 font-black uppercase text-[10px] tracking-widest">Diagnostic Order Pipeline Empty</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Inventory Monitoring -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-2xl h-fit">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest tracking-tighter">Infrastructure Status</h3>
                    <x-icons name="settings" class="w-4 h-4 text-slate-400 animate-spin-slow" />
                </div>
                
                <div class="space-y-4">
                    <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-800 relative overflow-hidden group hover:scale-[1.02] transition-all">
                        <div class="absolute right-0 top-0 w-16 h-16 bg-brand-green/5 rounded-bl-[2rem]"></div>
                        <p class="text-[9px] font-black text-brand-green uppercase tracking-widest mb-2 flex items-center gap-1">
                            <span class="w-1 h-1 bg-brand-green rounded-full"></span> Analyzer A1 Platform
                        </p>
                        <p class="text-slate-900 dark:text-white font-black text-sm uppercase tracking-tighter">Status: Nominal (Calibrated)</p>
                    </div>

                    <div class="p-6 bg-slate-50 dark:bg-slate-950 rounded-[2rem] border border-slate-100 dark:border-slate-800 relative overflow-hidden group hover:scale-[1.02] transition-all border-l-4 border-l-brand-teal">
                        <p class="text-[9px] font-black text-brand-teal uppercase tracking-widest mb-2">Reagent Inventory Registry</p>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-slate-900 dark:text-white font-black text-sm tracking-tighter">Glucose Assay Kits</span>
                            <span class="text-[10px] font-black text-brand-teal">20% Residual</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-teal rounded-full" style="width: 20%"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-50 dark:border-slate-800">
                    <button class="w-full py-4 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:scale-[1.02] transition-all active:scale-[0.98]">
                        Full Inventory Audit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>