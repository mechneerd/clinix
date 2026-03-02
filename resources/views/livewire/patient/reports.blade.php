<div class="p-6 lg:p-8 space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Health Reports</h1>
        <p class="text-slate-500 text-sm mt-1">Your complete visit history and medical records</p>
    </div>

    <div class="space-y-3">
        @forelse ($visits as $visit)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all cursor-pointer"
                 wire:click="viewReport({{ $visit->id }})">
                <div class="flex items-center gap-4 p-5">
                    <div class="flex-shrink-0 text-center w-14">
                        <div class="text-xl font-bold text-indigo-600">{{ $visit->completed_at?->format('d') ?? '?' }}</div>
                        <div class="text-xs text-slate-500 uppercase">{{ $visit->completed_at?->format('M Y') ?? '' }}</div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-xs font-bold text-indigo-600 flex-shrink-0">
                        {{ strtoupper(substr($visit->doctor->name ?? 'D', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-slate-900 dark:text-white text-sm mb-0.5">
                            Dr. {{ $visit->doctor->name ?? 'N/A' }}
                        </div>
                        <div class="text-xs text-slate-500 flex flex-wrap gap-x-3">
                            <span>{{ $visit->appointment->clinic->name ?? '' }}</span>
                            @if ($visit->diagnosis)
                                <span class="text-indigo-600 dark:text-indigo-400 truncate max-w-xs">Dx: {{ Str::limit($visit->diagnosis, 60) }}</span>
                            @endif
                        </div>
                    </div>
                    <flux:icon name="chevron-right" class="w-4 h-4 text-slate-400 flex-shrink-0" />
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    <flux:icon name="clipboard-document-list" class="w-8 h-8 text-slate-400" />
                </div>
                <h3 class="font-semibold text-slate-900 dark:text-white mb-2">No health reports yet</h3>
                <p class="text-slate-500 text-sm">Reports will appear here after completed doctor visits.</p>
            </div>
        @endforelse
    </div>

    {{ $visits->links() }}

    {{-- Visit Detail Modal --}}
    @if ($detail)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
             wire:click="closeReport">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
                 wire:click.stop>

                <div class="sticky top-0 bg-white dark:bg-slate-900 flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white">Visit Report</h3>
                        <p class="text-xs text-slate-500">
                            Dr. {{ $detail->doctor->name ?? '' }} · {{ $detail->completed_at?->format('M d, Y') ?? '' }}
                        </p>
                    </div>
                    <button wire:click="closeReport" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <flux:icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Vitals --}}
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">🩺 Vitals</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach ([
                                ['label' => 'Blood Pressure', 'value' => $detail->blood_pressure,           'unit' => ''],
                                ['label' => 'Pulse',          'value' => $detail->pulse_rate,               'unit' => 'bpm'],
                                ['label' => 'Temperature',    'value' => $detail->temperature,              'unit' => '°C'],
                                ['label' => 'SpO₂',           'value' => $detail->oxygen_saturation,        'unit' => '%'],
                                ['label' => 'Weight',         'value' => $detail->weight,                   'unit' => 'kg'],
                                ['label' => 'BMI',            'value' => $detail->bmi,                      'unit' => ''],
                            ] as $vital)
                                @if ($vital['value'])
                                    <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3 text-center">
                                        <div class="text-xs text-slate-500 mb-1">{{ $vital['label'] }}</div>
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $vital['value'] }} {{ $vital['unit'] }}</div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Diagnosis --}}
                    @if ($detail->diagnosis)
                        <div class="p-4 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-900">
                            <h4 class="text-xs font-semibold text-indigo-700 uppercase mb-1">Diagnosis</h4>
                            <p class="text-sm text-slate-800 dark:text-slate-200">{{ $detail->diagnosis }}</p>
                        </div>
                    @endif

                    {{-- Chief Complaint --}}
                    @if ($detail->chief_complaints)
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800">
                            <h4 class="text-xs font-semibold text-slate-500 uppercase mb-1">Chief Complaints</h4>
                            <p class="text-sm text-slate-800 dark:text-slate-200">{{ $detail->chief_complaints }}</p>
                        </div>
                    @endif

                    {{-- Examination --}}
                    @if ($detail->examination_findings)
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800">
                            <h4 class="text-xs font-semibold text-slate-500 uppercase mb-1">Examination Findings</h4>
                            <p class="text-sm text-slate-800 dark:text-slate-200">{{ $detail->examination_findings }}</p>
                        </div>
                    @endif

                    {{-- Prescriptions --}}
                    @if ($detail->prescriptions->count())
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">💊 Prescriptions ({{ $detail->prescriptions->count() }})</h4>
                            @foreach ($detail->prescriptions as $rx)
                                <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 mb-2">
                                    <div class="text-xs font-medium text-emerald-600 mb-1">{{ $rx->prescription_number }}</div>
                                    @foreach ($rx->items as $item)
                                        <div class="text-sm text-slate-700 dark:text-slate-300">
                                            • {{ $item->medicine_name }} {{ $item->strength }} — {{ $item->dosage }}, {{ $item->frequency }}, {{ $item->duration }}
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Lab Orders --}}
                    @if ($detail->labOrders->count())
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-2">🧪 Lab Tests ({{ $detail->labOrders->count() }})</h4>
                            @foreach ($detail->labOrders as $order)
                                <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-700 mb-2">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-medium text-violet-600">{{ $order->order_number }}</span>
                                        <span @class([
                                            'text-xs px-2 py-0.5 rounded-full',
                                            'bg-green-100 text-green-700' => $order->status === 'completed',
                                            'bg-amber-100 text-amber-700' => $order->status !== 'completed',
                                        ])>{{ ucfirst(str_replace('_',' ',$order->status)) }}</span>
                                    </div>
                                    @foreach ($order->items as $item)
                                        <div class="text-sm text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                            • {{ $item->labTest->name ?? 'Test' }}
                                            @if ($item->result_value)
                                                <span class="font-semibold">{{ $item->result_value }} {{ $item->result_unit }}</span>
                                                @if ($item->result_status)
                                                    <span @class(['text-xs', 'text-green-600'=>$item->result_status==='normal','text-red-600'=>$item->result_status!=='normal'])>
                                                        ({{ $item->result_status }})
                                                    </span>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif

</div>
