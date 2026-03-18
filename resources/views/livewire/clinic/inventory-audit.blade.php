<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Inventory Audit Ledger</h2>
            <p class="text-slate-500">Track every stock movement, adjustment, and dispense action.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 bg-rose-50 dark:bg-rose-500/10 rounded-xl border border-rose-100 dark:border-rose-500/20">
                <span class="text-xs font-bold text-rose-600 uppercase tracking-wider block">Low Stock Alerts</span>
                <span class="text-xl font-bold text-rose-700">{{ $stats['low_stock_medicines'] }} Items</span>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-emerald-100 dark:bg-emerald-500/10 flex items-center justify-center text-emerald-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
            </div>
            <div>
                <span class="text-sm text-slate-500 block">Total Stock In</span>
                <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_in']) }}</span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-rose-100 dark:bg-rose-500/10 flex items-center justify-center text-rose-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
            </div>
            <div>
                <span class="text-sm text-slate-500 block">Total Dispensed</span>
                <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total_out']) }}</span>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <span class="text-sm text-slate-500 block">Active Items</span>
                <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ \App\Models\Medicine::count() + \App\Models\LabConsumable::count() }}</span>
            </div>
        </div>
    </div>

    <!-- Ledger Table -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <!-- Filters -->
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-wrap items-center gap-4 justify-between">
            <div class="flex items-center gap-4 flex-1 min-w-[300px]">
                <div class="relative flex-1">
                    <input type="text" wire:model.live="search" placeholder="Search Reference/Notes..." class="w-full h-11 bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-10 focus:ring-2 focus:ring-brand-teal transition-all">
                    <x-icons name="search" class="w-5 h-5 text-slate-400 absolute left-3 top-3" />
                </div>
                <select wire:model.live="type" class="h-11 bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 focus:ring-2 focus:ring-brand-teal text-slate-600">
                    <option value="">All Actions</option>
                    <option value="purchase">Purchase</option>
                    <option value="dispense">Dispense</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="damaged">Damaged</option>
                </select>
                <select wire:model.live="resourceType" class="h-11 bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 focus:ring-2 focus:ring-brand-teal text-slate-600">
                    <option value="all">All Resources</option>
                    <option value="medicine">Medicines</option>
                    <option value="consumable">Consumables</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Timestamp</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Resource</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Action</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Quantity</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Performed By</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Reference</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($movements as $move)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-900 dark:text-white block">{{ $move->created_at->format('d M Y') }}</span>
                            <span class="text-[10px] text-slate-400">{{ $move->created_at->format('H:i A') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                    <x-icons :name="$move->stockable_type === 'App\Models\Medicine' ? 'pill' : 'box'" class="w-4 h-4" />
                                </div>
                                <span class="font-bold text-slate-700 dark:text-slate-200">{{ $move->stockable->name ?? 'Deleted Item' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'purchase' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'dispense' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'adjustment' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'damaged' => 'bg-rose-50 text-rose-600 border-rose-100',
                                ];
                                $color = $colors[$move->type] ?? 'bg-slate-50 text-slate-600 border-slate-100';
                            @endphp
                            <span class="px-2.5 py-1 rounded-md border {{ $color }} text-[10px] font-bold uppercase tracking-wider">
                                {{ $move->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold {{ $move->quantity > 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                                {{ $move->quantity > 0 ? '+' : '' }}{{ $move->quantity }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                             <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-[10px] font-bold uppercase">
                                    {{ substr($move->creator->name ?? '?', 0, 1) }}
                                </div>
                                <span class="text-sm text-slate-600 dark:text-slate-400">{{ $move->creator->name ?? 'System' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-xs font-mono text-slate-400 bg-slate-50 dark:bg-slate-800 px-2 py-1 rounded">{{ $move->reference_id ?? 'N/A' }}</span>
                            @if($move->notes)
                                <p class="text-[10px] text-slate-400 mt-1 italic">{{ $move->notes }}</p>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-slate-400">
                            <div class="flex flex-col items-center">
                                <x-icons name="inbox" class="w-12 h-12 mb-4 opacity-20" />
                                <p>No stock movements found matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-6 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
            {{ $movements->links() }}
        </div>
    </div>
</div>
