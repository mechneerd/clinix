<div class="space-y-8 pb-20">
    <!-- Top Bar / Navigation -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <a href="{{ route('doctor.dashboard') }}" wire:navigate class="p-2 rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-slate-500 hover:text-brand-teal transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Clinical Encounter</h2>
                <p class="text-slate-500 text-sm">Appointment #{{ $appointment->id }} • {{ $appointment->appointment_date->format('M d, Y') }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <span class="px-4 py-2 rounded-full bg-brand-teal/10 text-brand-teal font-semibold text-sm animate-pulse">
                Consultation in Progress
            </span>
            <button wire:click="saveEncounter" class="px-6 py-2.5 bg-brand-teal text-white rounded-xl font-bold shadow-lg shadow-brand-teal/20 hover:scale-[1.02] transition-all">
                Finalize & Close
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Left Column: Patient Profile & History -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Patient Card -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
                <div class="flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-3xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-4 border-4 border-white dark:border-slate-900 shadow-xl overflow-hidden">
                         <span class="text-3xl font-bold text-slate-400 capitalize">{{ substr($patient->first_name, 0, 1) }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">{{ $patient->full_name }}</h3>
                    <p class="text-slate-500 text-sm">{{ $patient->patient_code }} • {{ $patient->age }} years • {{ $patient->gender }}</p>
                </div>

                <div class="mt-8 space-y-4">
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-sm text-slate-500">Blood Group</span>
                        <span class="font-bold text-rose-500">{{ $patient->blood_group ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-slate-50 dark:bg-slate-800/50">
                        <span class="text-sm text-slate-500">Last Visit</span>
                        <span class="font-medium dark:text-white">{{ $patient->appointments()->where('status', 'completed')->latest()->first()?->appointment_date?->format('d M Y') ?? 'First Visit' }}</span>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4 text-center">Known Allergies</h4>
                    @php 
                        $allergies = $patient->allergies()->get();
                    @endphp
                    <div class="flex flex-wrap justify-center">
                    @forelse($allergies as $allergy)
                        <span class="inline-block px-3 py-1 rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-600 text-[10px] font-bold mr-2 mb-2 uppercase tracking-tight">
                            {{ $allergy->allergen }}
                        </span>
                    @empty
                        <p class="text-sm text-slate-400 italic">No allergies reported</p>
                    @endforelse
                    </div>
                </div>
            </div>

            <!-- Complaint -->
            <div class="bg-brand-teal/5 rounded-[2.5rem] p-8 border border-brand-teal/10">
                <h4 class="text-lg font-bold text-brand-teal mb-2">Patient Complaint</h4>
                <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed italic">
                    "{{ $appointment->chief_complaint ?? 'No complaint recorded at check-in.' }}"
                </p>
            </div>
        </div>

        <!-- Right Column: Encounter Form -->
        <div class="lg:col-span-8 space-y-8">
            <!-- Vitals Grid -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Patient Vitals</h3>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Blood Pressure</label>
                        <input type="text" wire:model="vitals.blood_pressure" placeholder="120/80" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Temp (°C)</label>
                        <input type="number" step="0.1" wire:model="vitals.temperature" placeholder="37.0" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pulse (BPM)</label>
                        <input type="number" wire:model="vitals.pulse" placeholder="72" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">SpO2 (%)</label>
                        <input type="number" wire:model="vitals.oxygen_saturation" placeholder="98" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Weight (kg)</label>
                        <input type="number" step="0.1" wire:model="vitals.weight" placeholder="70.5" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Height (cm)</label>
                        <input type="number" wire:model="vitals.height" placeholder="175" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all">
                    </div>
                </div>
            </div>

            <!-- Diagnosis & Symptoms -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Clinical Assessment</h3>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Symptoms & Observations</label>
                        <textarea wire:model="symptoms" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 py-3 focus:ring-2 focus:ring-brand-teal transition-all" placeholder="Enter patient symptoms..."></textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Diagnosis <span class="text-rose-500">*</span></label>
                        <input type="text" wire:model="diagnosis" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all" placeholder="Primary diagnosis...">
                        @error('diagnosis') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Treatment Plan</label>
                        <textarea wire:model="treatment_plan" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 py-3 focus:ring-2 focus:ring-brand-teal transition-all" placeholder="Enter treatment plan..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Prescription Builder -->
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 p-8 shadow-sm overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 dark:bg-purple-500/10 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Prescriptions</h3>
                    </div>
                    
                    <div class="relative w-64" x-data="{ open: false }">
                        <input 
                            type="text" 
                            @focus="open = true" 
                            @click.away="open = false"
                            placeholder="Search Medicine..." 
                            class="w-full h-11 bg-slate-50 dark:bg-slate-800 border-none rounded-xl text-sm px-4 focus:ring-2 focus:ring-brand-teal"
                            wire:model.live="searchMedicine"
                        >
                        <div x-show="open && $wire.searchMedicine.length > 0" class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 max-h-60 overflow-y-auto">
                            @foreach($availableMedicines as $med)
                                @if(stripos($med->name, $searchMedicine) !== false)
                                <button wire:click="addPrescriptionItem({{ $med->id }})" @click="open = false" class="w-full text-left px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700 transition-all border-b border-slate-100 dark:border-slate-700 last:border-0">
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $med->name }}</div>
                                    <div class="text-[10px] text-slate-500 uppercase tracking-tighter">{{ $med->medicineCategory?->name ?? $med->category }} • Total Stock: {{ $med->stock_quantity }}</div>
                                </button>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                @if(empty($prescriptionItems))
                    <div class="py-12 flex flex-col items-center justify-center border-2 border-dashed border-slate-100 dark:border-slate-800 rounded-[2rem]">
                        <p class="text-slate-400 font-medium">No medicines prescribed yet.</p>
                        <p class="text-[10px] text-slate-300 mt-1 uppercase tracking-widest font-bold font-bold">Search above to add items</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach($prescriptionItems as $index => $item)
                        <div class="relative p-6 rounded-[2rem] bg-slate-50 dark:bg-slate-800/30 border border-slate-100 dark:border-slate-800 group animate-in slide-in-from-right duration-300">
                            <div class="flex items-center justify-between mb-4">
                                <span class="font-black text-slate-900 dark:text-white">{{ $item['name'] }}</span>
                                <button wire:click="removePrescriptionItem({{ $index }})" class="p-2 text-slate-300 hover:text-rose-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Dosage</label>
                                    <input type="text" wire:model="prescriptionItems.{{ $index }}.dosage" placeholder="eg. 1 tab" class="w-full h-10 bg-white dark:bg-slate-900 border-none rounded-xl text-sm px-4 focus:ring-1 focus:ring-brand-teal">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Frequency</label>
                                    <input type="text" wire:model="prescriptionItems.{{ $index }}.frequency" placeholder="eg. TDS" class="w-full h-10 bg-white dark:bg-slate-900 border-none rounded-xl text-sm px-4 focus:ring-1 focus:ring-brand-teal">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Duration</label>
                                    <input type="text" wire:model="prescriptionItems.{{ $index }}.duration" placeholder="eg. 5 days" class="w-full h-10 bg-white dark:bg-slate-900 border-none rounded-xl text-sm px-4 focus:ring-1 focus:ring-brand-teal">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Qty</label>
                                    <input type="number" wire:model="prescriptionItems.{{ $index }}.quantity" class="w-full h-10 bg-white dark:bg-slate-900 border-none rounded-xl text-sm px-4 focus:ring-1 focus:ring-brand-teal">
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center gap-4">
                                <div class="flex-1">
                                    <label class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter block mb-1">Select Batch (Expiry Validation)</label>
                                    <select wire:model="prescriptionItems.{{ $index }}.medicine_batch_id" class="w-full h-10 bg-white dark:bg-slate-900 border-none rounded-xl text-xs px-4 focus:ring-1 focus:ring-brand-teal">
                                        <option value="">Select Batch...</option>
                                        @foreach($item['batches'] as $batch)
                                            <option value="{{ $batch['id'] }}">
                                                Batch: {{ $batch['batch_number'] }} • Exp: {{ \Carbon\Carbon::parse($batch['expiry_date'])->format('d M Y') }} • Qty: {{ $batch['quantity'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
