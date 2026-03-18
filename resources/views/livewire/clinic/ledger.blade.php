<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Global Ledger</h2>
            <p class="text-slate-500 text-sm">Double-entry accounting, real-time fiscal audit, and COA.</p>
        </div>
        <div class="flex gap-3">
             <button class="px-5 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-600 hover:text-brand-teal transition-all">
                Trial Balance
            </button>
            <button class="px-6 py-2.5 bg-brand-teal text-white rounded-xl font-bold shadow-lg shadow-brand-teal/20 hover:scale-[1.02] transition-all">
                Manual Journal Entry
            </button>
        </div>
    </div>

    <!-- Financial Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-brand-teal p-8 rounded-[2.5rem] shadow-xl shadow-brand-teal/20 relative overflow-hidden group">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-all duration-700"></div>
            <p class="text-[10px] font-bold text-white/60 uppercase tracking-widest mb-1">Total Revenue</p>
            <h3 class="text-4xl font-black text-white">${{ number_format($totalRevenue, 2) }}</h3>
            <div class="mt-6 flex items-center gap-2">
                <span class="p-1 px-2 rounded-lg bg-white/20 text-white text-[10px] font-black uppercase">+12% vs last month</span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Expenses</p>
            <h3 class="text-4xl font-black text-slate-900 dark:text-white">${{ number_format($totalExpenses, 2) }}</h3>
            <p class="text-[10px] text-rose-500 font-bold mt-2 uppercase tracking-tighter">Budget Utilization: 68%</p>
        </div>
        <div class="bg-slate-900 text-white p-8 rounded-[2.5rem] shadow-2xl relative overflow-hidden group">
             <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-brand-teal/10 rounded-full blur-2xl group-hover:scale-150 transition-all duration-700"></div>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Net Cash Position</p>
            <h3 class="text-4xl font-black text-brand-green">${{ number_format($totalRevenue - $totalExpenses, 2) }}</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase">Healthy Cashflow</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- COA Accounts -->
        <div class="lg:col-span-8">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Chart of Accounts (COA)</h3>
                    <button class="text-[10px] font-bold text-brand-teal uppercase tracking-widest">Manage Accounts</button>
                </div>
                <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($accounts as $acc)
                    <div class="p-5 rounded-3xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 hover:border-brand-teal transition-all cursor-pointer group">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2 py-0.5 rounded-md bg-slate-50 dark:bg-slate-900 text-[9px] font-black text-slate-400 uppercase">{{ $acc->code }}</span>
                            <span class="text-[9px] font-bold uppercase tracking-tight {{ $acc->type === 'asset' ? 'text-emerald-500' : 'text-rose-500' }}">{{ $acc->type }}</span>
                        </div>
                        <h4 class="font-bold text-slate-800 dark:text-white capitalize mb-1">{{ $acc->name }}</h4>
                        <p class="text-lg font-black text-slate-900 dark:text-white group-hover:text-brand-teal transition-colors">${{ number_format($acc->balance, 2) }}</p>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-slate-400 font-medium">No accounts configured. Start by generating COA.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="lg:col-span-4">
             <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm p-8">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-8">Quick Ledger</h3>
                <div class="space-y-6">
                    @forelse($recentTransactions as $tx)
                    <div class="flex items-center gap-4 group">
                        <div class="w-10 h-10 rounded-xl {{ $tx->debit > 0 ? 'bg-rose-50 text-rose-500' : 'bg-emerald-50 text-emerald-500' }} flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tx->debit > 0 ? 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' : 'M11 7H3m0 0v8m0-8l8 8 4-4 6 6' }}"></path></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-900 dark:text-white truncate uppercase tracking-tighter">{{ $tx->description }}</p>
                            <p class="text-[9px] text-slate-400 uppercase font-medium">{{ $tx->account->name }} • {{ $tx->transaction_date->format('H:i') }}</p>
                        </div>
                        <div class="text-right">
                             <p class="text-xs font-black {{ $tx->debit > 0 ? 'text-rose-500' : 'text-emerald-500' }}">
                                {{ $tx->debit > 0 ? '-$' . number_format($tx->debit, 2) : '+$' . number_format($tx->credit, 2) }}
                             </p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-slate-400 py-10 italic">No recent activity.</p>
                    @endforelse
                </div>
                <button class="w-full mt-8 py-3 bg-slate-50 dark:bg-slate-800 text-slate-500 font-bold rounded-2xl text-xs uppercase tracking-widest hover:text-brand-teal transition-all">Full Transaction Log</button>
            </div>
        </div>
    </div>
</div>
