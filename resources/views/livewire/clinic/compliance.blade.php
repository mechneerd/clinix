<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Compliance & Safety</h2>
            <p class="text-slate-500 text-sm">Incident reporting, clinical safety audits, and risk management.</p>
        </div>
        <button class="px-6 py-2.5 bg-rose-500 text-white rounded-xl font-bold shadow-lg shadow-rose-500/20 hover:scale-[1.02] transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            Report Incident
        </button>
    </div>

    <!-- Safety Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Reported Incidents</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $totalIncidents }}</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-tighter italic">Last 365 Days</p>
        </div>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border-rose-100 dark:border-rose-900/30 border-2 shadow-sm">
            <p class="text-[10px] font-bold text-rose-400 uppercase tracking-widest mb-1">Unresolved Safety Risks</p>
            <h3 class="text-3xl font-black text-rose-500">{{ $unresolvedIncidents }}</h3>
            <p class="text-[10px] text-rose-400 font-black mt-2 uppercase animate-pulse">Critical Priority</p>
        </div>
        <div class="bg-slate-900 text-white p-8 rounded-[2.5rem] shadow-2xl">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">System Integrity Audit</p>
            <h3 class="text-3xl font-black text-emerald-500">OPTIMAL</h3>
            <div class="mt-4 flex gap-1">
                <div class="w-full h-1 bg-emerald-500 rounded-full"></div>
                <div class="w-1/2 h-1 bg-slate-700 rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Incidents -->
        <div class="lg:col-span-12">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Clinical Audit Trail (Real-time)</h3>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">User</th>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Action</th>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Resource Type</th>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Timestamp</th>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Original State</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($auditLogs as $log)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $log->user->name }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-[10px] font-bold uppercase tracking-widest">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-xs text-slate-500 font-medium">{{ class_basename($log->auditable_type) }} #{{ $log->auditable_id }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="text-xs text-slate-600 dark:text-slate-400">{{ $log->created_at->format('d M, H:i:s') }}</span>
                            </td>
                            <td class="px-8 py-4">
                                <button class="text-[9px] font-black text-brand-teal uppercase tracking-widest hover:underline">View Payload</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
