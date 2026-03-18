<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Workforce Management</h2>
            <p class="text-slate-500 text-sm">Enterprise-grade HR, Payroll, and Performance tracking.</p>
        </div>
        <div class="flex gap-3">
             <button class="px-5 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl text-sm font-bold text-slate-600 hover:text-brand-teal transition-all">
                Export Reports
            </button>
            <button class="px-6 py-2.5 bg-brand-teal text-white rounded-xl font-bold shadow-lg shadow-brand-teal/20 hover:scale-[1.02] transition-all">
                Add New Position
            </button>
        </div>
    </div>

    <!-- HR Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Active Staff</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $staffCount }}</h3>
            <p class="text-[10px] text-emerald-500 font-bold mt-2 uppercase">Full Strength</p>
        </div>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Open Positions</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white">{{ $positions }}</h3>
            <p class="text-[10px] text-brand-teal font-bold mt-2 uppercase">Hiring Active</p>
        </div>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pending Leaves</p>
            <h3 class="text-3xl font-black text-rose-500">{{ $pendingLeaves }}</h3>
            <p class="text-[10px] text-rose-400 font-bold mt-2 uppercase">Action Required</p>
        </div>
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Payroll (Current)</p>
            <h3 class="text-3xl font-black text-slate-900 dark:text-white">${{ number_format($totalPayroll, 0) }}</h3>
            <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase">Month to Date</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Recent Leave Requests -->
        <div class="lg:col-span-12">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Recent Leave Requests</h3>
                    <a href="#" class="text-brand-teal text-xs font-bold uppercase tracking-widest">View All</a>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50 dark:bg-slate-800/50">
                        <tr>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Employee</th>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Type</th>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Duration</th>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Reason</th>
                            <th class="px-8 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($recentLeaves as $leave)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <p class="font-bold text-slate-900 dark:text-white">{{ $leave->staff->user->name }}</p>
                                <span class="text-[10px] text-slate-400 uppercase font-medium">{{ $leave->staff->role }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <span class="text-sm text-slate-600 dark:text-slate-400">{{ $leave->leave_type }}</span>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d') }}</p>
                                <p class="text-[10px] text-slate-400">{{ $leave->start_date->diffInDays($leave->end_date) + 1 }} Days</p>
                            </td>
                            <td class="px-8 py-5">
                                <p class="text-xs text-slate-500 italic">"{{ $leave->reason }}"</p>
                            </td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $leave->status === 'approved' ? 'bg-emerald-50 text-emerald-600' : ($leave->status === 'pending' ? 'bg-orange-50 text-orange-600' : 'bg-rose-50 text-rose-600') }}">
                                    {{ $leave->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <p class="text-slate-400 font-medium">No leave requests found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
