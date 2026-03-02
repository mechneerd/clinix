<div class="p-6 lg:p-8 space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Lab Results</h1>
        <p class="text-slate-500 text-sm mt-1">View all your laboratory test orders and results</p>
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-2">
        @foreach ([''=>'All','ordered'=>'Ordered','in_progress'=>'In Progress','completed'=>'Completed','cancelled'=>'Cancelled'] as $val => $label)
            <button wire:click="$set('statusFilter','{{ $val }}')"
                    @class([
                        'px-4 py-1.5 rounded-full text-sm font-medium transition-all',
                        'bg-violet-600 text-white shadow-sm' => $statusFilter === $val,
                        'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:border-violet-400' => $statusFilter !== $val,
                    ])>{{ $label }}</button>
        @endforeach
    </div>

    {{-- Orders list --}}
    <div class="space-y-3">
        @forelse ($orders as $order)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                 wire:click="viewOrder({{ $order->id }})">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 p-5">
                    <div class="w-12 h-12 rounded-xl bg-violet-50 dark:bg-violet-900/20 flex items-center justify-center flex-shrink-0">
                        <flux:icon name="beaker" class="w-6 h-6 text-violet-600 dark:text-violet-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $order->order_number }}</span>
                            <span @class([
                                'text-xs px-2.5 py-0.5 rounded-full font-medium',
                                'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400'     => $order->status === 'ordered',
                                'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' => in_array($order->status, ['sample_collected','sample_received','in_progress']),
                                'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' => $order->status === 'completed',
                                'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'         => $order->status === 'cancelled',
                            ])>{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>

                            @if ($order->priority !== 'routine')
                                <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700 font-medium uppercase">
                                    {{ $order->priority }}
                                </span>
                            @endif
                        </div>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 text-sm text-slate-500">
                            <span>Lab: {{ $order->lab->name ?? 'N/A' }}</span>
                            <span>Doctor: Dr. {{ $order->doctor->name ?? 'N/A' }}</span>
                            <span>{{ $order->items->count() }} test(s)</span>
                            <span>{{ $order->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        @if ($order->report)
                            <flux:button size="xs" class="bg-violet-600 text-white border-0 rounded-lg" icon="arrow-down-tray">
                                Report
                            </flux:button>
                        @endif
                        <flux:icon name="chevron-right" class="w-4 h-4 text-slate-400" />
                    </div>
                </div>

                {{-- Progress bar for in-progress --}}
                @if (in_array($order->status, ['sample_collected','sample_received','in_progress']))
                    @php
                        $progress = match($order->status) {
                            'sample_collected' => 33,
                            'sample_received'  => 60,
                            'in_progress'      => 80,
                            default            => 0,
                        };
                    @endphp
                    <div class="px-5 pb-4">
                        <div class="flex justify-between text-xs text-slate-500 mb-1">
                            <span>Processing</span><span>{{ $progress }}%</span>
                        </div>
                        <div class="h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-400 rounded-full transition-all" style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    <flux:icon name="beaker" class="w-8 h-8 text-slate-400" />
                </div>
                <h3 class="font-semibold text-slate-900 dark:text-white mb-2">No lab orders found</h3>
                <p class="text-slate-500 text-sm">Your doctor will order lab tests during your visit.</p>
            </div>
        @endforelse
    </div>

    {{ $orders->links() }}

    {{-- Detail Modal --}}
    @if ($detail)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
             wire:click="closeOrder">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
                 wire:click.stop>
                {{-- Header --}}
                <div class="sticky top-0 bg-white dark:bg-slate-900 flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white">{{ $detail->order_number }}</h3>
                        <p class="text-xs text-slate-500">{{ $detail->lab->name ?? '' }} · {{ $detail->created_at->format('M d, Y') }}</p>
                    </div>
                    <button wire:click="closeOrder" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center hover:bg-slate-200">
                        <flux:icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    {{-- Test results --}}
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">Test Results</h4>
                        <div class="space-y-3">
                            @foreach ($detail->items as $item)
                                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="font-medium text-slate-900 dark:text-white text-sm">{{ $item->labTest->name ?? 'N/A' }}</span>
                                        @if ($item->result_status)
                                            <span @class([
                                                'text-xs px-2 py-0.5 rounded-full font-medium',
                                                'bg-green-100 text-green-700' => $item->result_status === 'normal',
                                                'bg-amber-100 text-amber-700' => $item->result_status === 'abnormal',
                                                'bg-red-100 text-red-700'     => $item->result_status === 'critical',
                                            ])>{{ ucfirst($item->result_status) }}</span>
                                        @endif
                                    </div>
                                    @if ($item->result_value)
                                        <div class="flex items-center gap-2">
                                            <span class="text-lg font-bold text-slate-900 dark:text-white">{{ $item->result_value }}</span>
                                            <span class="text-sm text-slate-500">{{ $item->result_unit }}</span>
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-400 italic">Pending</span>
                                    @endif
                                    @if ($item->notes_for_patient)
                                        <p class="text-xs text-slate-500 mt-1">{{ $item->notes_for_patient }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if ($detail->report?->file_path)
                        <a href="{{ $detail->report->file_url }}" target="_blank">
                            <flux:button class="w-full bg-violet-600 text-white border-0 rounded-xl" icon="arrow-down-tray">
                                Download Full Report (PDF)
                            </flux:button>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

</div>
