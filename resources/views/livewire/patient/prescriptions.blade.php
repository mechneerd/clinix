<div class="p-6 lg:p-8 space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">My Prescriptions</h1>
        <p class="text-slate-500 text-sm mt-1">View all prescriptions issued by your doctors</p>
    </div>

    <div class="space-y-3">
        @forelse ($prescriptions as $rx)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all cursor-pointer"
                 wire:click="viewPrescription({{ $rx->id }})">
                <div class="flex items-center gap-4 p-5">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center flex-shrink-0">
                        <flux:icon name="document-text" class="w-6 h-6 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-semibold text-slate-900 dark:text-white">{{ $rx->prescription_number }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700 font-medium">Finalized</span>
                        </div>
                        <div class="flex flex-wrap gap-x-4 text-sm text-slate-500">
                            <span>Dr. {{ $rx->doctor->name ?? 'N/A' }}</span>
                            <span>{{ $rx->items->count() }} medication(s)</span>
                            <span>{{ $rx->created_at->format('M d, Y') }}</span>
                            @if ($rx->follow_up_date)
                                <span class="text-amber-600">Follow-up: {{ $rx->follow_up_date->format('M d, Y') }}</span>
                            @endif
                        </div>
                    </div>
                    <flux:icon name="chevron-right" class="w-4 h-4 text-slate-400 flex-shrink-0" />
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    <flux:icon name="document-text" class="w-8 h-8 text-slate-400" />
                </div>
                <h3 class="font-semibold text-slate-900 dark:text-white mb-2">No prescriptions yet</h3>
                <p class="text-slate-500 text-sm">Prescriptions will appear here after your doctor visits.</p>
            </div>
        @endforelse
    </div>

    {{ $prescriptions->links() }}

    {{-- Prescription Detail Modal --}}
    @if ($detail)
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
             wire:click="closePrescription">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
                 wire:click.stop>

                {{-- Header --}}
                <div class="sticky top-0 bg-white dark:bg-slate-900 flex items-center justify-between p-6 border-b border-slate-100 dark:border-slate-800">
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white">{{ $detail->prescription_number }}</h3>
                        <p class="text-xs text-slate-500">Dr. {{ $detail->doctor->name ?? '' }} · {{ $detail->created_at->format('M d, Y') }}</p>
                    </div>
                    <button wire:click="closePrescription" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <flux:icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>

                <div class="p-6 space-y-6">

                    {{-- Clinical notes --}}
                    @if ($detail->clinical_notes)
                        <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900">
                            <h4 class="text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase mb-1">Clinical Notes</h4>
                            <p class="text-sm text-slate-700 dark:text-slate-300">{{ $detail->clinical_notes }}</p>
                        </div>
                    @endif

                    {{-- Medications --}}
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-3">💊 Medications</h4>
                        <div class="space-y-3">
                            @foreach ($detail->items as $item)
                                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                                    <div class="flex items-start justify-between gap-2 mb-2">
                                        <div>
                                            <span class="font-semibold text-slate-900 dark:text-white">{{ $item->medicine_name }}</span>
                                            @if ($item->strength)
                                                <span class="ml-1 text-sm text-slate-500">{{ $item->strength }}</span>
                                            @endif
                                        </div>
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400 flex-shrink-0">
                                            {{ $item->medicine_type }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-3 text-xs">
                                        <div class="bg-white dark:bg-slate-900 rounded-lg p-2 text-center">
                                            <div class="text-slate-500 mb-0.5">Dosage</div>
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ $item->dosage }}</div>
                                        </div>
                                        <div class="bg-white dark:bg-slate-900 rounded-lg p-2 text-center">
                                            <div class="text-slate-500 mb-0.5">Frequency</div>
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ $item->frequency }}</div>
                                        </div>
                                        <div class="bg-white dark:bg-slate-900 rounded-lg p-2 text-center">
                                            <div class="text-slate-500 mb-0.5">Duration</div>
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ $item->duration }}</div>
                                        </div>
                                    </div>
                                    @if ($item->instructions)
                                        <p class="text-xs text-slate-500 mt-2">⚠️ {{ $item->instructions }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Instructions --}}
                    @if ($detail->special_instructions || $detail->dietary_advice || $detail->follow_up_date)
                        <div class="space-y-2">
                            @if ($detail->special_instructions)
                                <div class="p-3 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-900">
                                    <p class="text-xs font-semibold text-amber-700 mb-0.5">Special Instructions</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300">{{ $detail->special_instructions }}</p>
                                </div>
                            @endif
                            @if ($detail->dietary_advice)
                                <div class="p-3 rounded-xl bg-green-50 dark:bg-green-900/20 border border-green-100 dark:border-green-900">
                                    <p class="text-xs font-semibold text-green-700 mb-0.5">Dietary Advice</p>
                                    <p class="text-sm text-slate-700 dark:text-slate-300">{{ $detail->dietary_advice }}</p>
                                </div>
                            @endif
                            @if ($detail->follow_up_date)
                                <div class="p-3 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-900">
                                    <p class="text-xs font-semibold text-indigo-700 mb-0.5">Follow-up Date</p>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $detail->follow_up_date->format('l, F j, Y') }}</p>
                                </div>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    @endif

</div>
