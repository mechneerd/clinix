<div class="p-6 lg:p-8 max-w-3xl mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Book an Appointment</h1>
        <p class="text-slate-500 text-sm mt-1">Step {{ $step }} of 3</p>
    </div>

    {{-- Progress --}}
    <div class="flex items-center gap-2 mb-10">
        @foreach ([1=>'Choose Doctor', 2=>'Visit Details', 3=>'Confirm'] as $i => $label)
            <div class="flex items-center {{ $i < 3 ? 'flex-1' : '' }}">
                <div @class([
                    'w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all flex-shrink-0',
                    'bg-emerald-500 text-white shadow-lg shadow-emerald-500/30' => $step >= $i,
                    'bg-slate-200 dark:bg-slate-700 text-slate-500' => $step < $i,
                ])>
                    @if ($step > $i) ✓ @else {{ $i }} @endif
                </div>
                <div class="ml-2 hidden sm:block text-xs {{ $step >= $i ? 'text-emerald-600 font-medium' : 'text-slate-500' }}">{{ $label }}</div>
                @if ($i < 3)
                    <div @class(['flex-1 h-0.5 mx-3', 'bg-emerald-400' => $step > $i, 'bg-slate-200 dark:bg-slate-700' => $step <= $i])></div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">

        {{-- Step 1: Pick Clinic / Doctor / Date / Slot --}}
        @if ($step === 1)
        <h2 class="font-semibold text-slate-900 dark:text-white mb-6">Choose your doctor</h2>

        <div class="space-y-4">
            <div>
                <flux:label class="text-sm text-slate-600 dark:text-slate-300">Clinic</flux:label>
                <flux:select wire:model.live="clinicId" class="mt-1 w-full rounded-xl dark:bg-slate-800 dark:border-slate-700">
                    <option value="">Select a clinic…</option>
                    @foreach ($clinics as $clinic)
                        <option value="{{ $clinic->id }}">{{ $clinic->name }} — {{ $clinic->city }}</option>
                    @endforeach
                </flux:select>
                @error('clinicId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            @if ($clinicId)
            <div>
                <flux:label class="text-sm text-slate-600 dark:text-slate-300">Department <span class="text-slate-400">(optional)</span></flux:label>
                <flux:select wire:model.live="departmentId" class="mt-1 w-full rounded-xl dark:bg-slate-800 dark:border-slate-700">
                    <option value="">All departments</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </flux:select>
            </div>

            <div>
                <flux:label class="text-sm text-slate-600 dark:text-slate-300">Doctor</flux:label>
                <div class="mt-1 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @forelse ($doctors as $doctor)
                        <button wire:click="$set('doctorId', {{ $doctor->id }})"
                                @class([
                                    'flex items-center gap-3 p-3 rounded-xl border-2 text-left transition-all',
                                    'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20' => $doctorId === $doctor->id,
                                    'border-slate-200 dark:border-slate-700 hover:border-emerald-300' => $doctorId !== $doctor->id,
                                ])>
                            <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-sm font-bold text-indigo-600 flex-shrink-0">
                                {{ strtoupper(substr($doctor->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-slate-900 dark:text-white truncate">Dr. {{ $doctor->name }}</div>
                                <div class="text-xs text-slate-500">{{ $doctor->staffProfile->qualification ?? 'General Practice' }}</div>
                            </div>
                            @if ($doctorId === $doctor->id)
                                <div class="ml-auto w-5 h-5 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @endif
                        </button>
                    @empty
                        <p class="text-sm text-slate-500 col-span-2 py-4 text-center">No doctors available for this clinic.</p>
                    @endforelse
                </div>
                @error('doctorId') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            @if ($doctorId)
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Appointment Date</flux:label>
                    <flux:input wire:model.live="date" type="date" class="mt-1 w-full rounded-xl"
                                min="{{ today()->format('Y-m-d') }}" />
                    @error('date') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            @if (!empty($availableSlots))
            <div>
                <flux:label class="text-sm text-slate-600 dark:text-slate-300">Available Time Slots</flux:label>
                <div class="mt-2 grid grid-cols-3 sm:grid-cols-4 gap-2">
                    @foreach ($availableSlots as $slot)
                        @if ($slot['available'])
                            <button wire:click="$set('selectedSlot', '{{ $slot['time'] }}')"
                                    @class([
                                        'py-2 rounded-xl text-xs font-medium border-2 transition-all',
                                        'border-emerald-500 bg-emerald-500 text-white' => $selectedSlot === $slot['time'],
                                        'border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-emerald-400' => $selectedSlot !== $slot['time'],
                                    ])>
                                {{ $slot['label'] }}
                            </button>
                        @else
                            <button disabled class="py-2 rounded-xl text-xs border-2 border-slate-100 dark:border-slate-800 text-slate-400 bg-slate-50 dark:bg-slate-800/50 cursor-not-allowed line-through">
                                {{ $slot['label'] }}
                            </button>
                        @endif
                    @endforeach
                </div>
                @error('selectedSlot') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            @elseif ($date && $doctorId)
                <p class="text-sm text-amber-600 bg-amber-50 dark:bg-amber-900/20 rounded-xl px-4 py-3">
                    No available slots for this doctor on selected date. Please choose another date.
                </p>
            @endif
            @endif
            @endif

        </div>

        <div class="mt-6 flex justify-end">
            <flux:button wire:click="nextStep"
                         class="bg-emerald-600 hover:bg-emerald-700 text-white border-0 rounded-xl px-8">
                Continue →
            </flux:button>
        </div>
        @endif

        {{-- Step 2: Visit Details --}}
        @if ($step === 2)
        <h2 class="font-semibold text-slate-900 dark:text-white mb-6">Visit details</h2>

        <div class="space-y-4">
            <div>
                <flux:label class="text-sm text-slate-600 dark:text-slate-300">Appointment Type</flux:label>
                <div class="mt-2 grid grid-cols-2 gap-2">
                    @foreach (['in_person'=>['🏥','In Person'], 'online'=>['💻','Online']] as $val => [$emoji, $label])
                        <button wire:click="$set('type', '{{ $val }}')"
                                @class([
                                    'flex items-center gap-2 p-3 rounded-xl border-2 text-sm font-medium transition-all',
                                    'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-300' => $type === $val,
                                    'border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300' => $type !== $val,
                                ])>
                            {{ $emoji }} {{ $label }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div>
                <flux:label class="text-sm text-slate-600 dark:text-slate-300">Symptoms / Chief Complaint</flux:label>
                <flux:textarea wire:model="symptoms" rows="3" placeholder="Describe your symptoms…"
                               class="mt-1 w-full rounded-xl dark:bg-slate-800 dark:border-slate-700" />
            </div>

            <div>
                <flux:label class="text-sm text-slate-600 dark:text-slate-300">Additional Notes <span class="text-slate-400">(optional)</span></flux:label>
                <flux:textarea wire:model="notes" rows="2" placeholder="Any additional information for the doctor…"
                               class="mt-1 w-full rounded-xl dark:bg-slate-800 dark:border-slate-700" />
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <flux:button wire:click="prevStep" variant="ghost" class="border-slate-200 dark:border-slate-700 rounded-xl">
                ← Back
            </flux:button>
            <flux:button wire:click="nextStep"
                         class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white border-0 rounded-xl">
                Review Booking →
            </flux:button>
        </div>
        @endif

        {{-- Step 3: Confirm --}}
        @if ($step === 3)
        <h2 class="font-semibold text-slate-900 dark:text-white mb-6">Review & confirm</h2>

        <div class="space-y-3 mb-6">
            @foreach ([
                ['icon' => 'building-office-2', 'label' => 'Clinic',   'value' => $clinics->firstWhere('id', $clinicId)?->name ?? '—'],
                ['icon' => 'user',              'label' => 'Doctor',   'value' => 'Dr. ' . ($doctors->firstWhere('id', $doctorId)?->name ?? '—')],
                ['icon' => 'calendar-days',     'label' => 'Date',     'value' => $date ? \Carbon\Carbon::parse($date)->format('l, F j, Y') : '—'],
                ['icon' => 'clock',             'label' => 'Time',     'value' => $selectedSlot ? \Carbon\Carbon::parse($selectedSlot)->format('h:i A') : '—'],
                ['icon' => 'tag',               'label' => 'Type',     'value' => ucfirst(str_replace('_', ' ', $type))],
            ] as $row)
                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800">
                    <flux:icon :name="$row['icon']" class="w-5 h-5 text-emerald-500 flex-shrink-0" />
                    <span class="text-sm text-slate-500 w-20 flex-shrink-0">{{ $row['label'] }}</span>
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $row['value'] }}</span>
                </div>
            @endforeach

            @if ($symptoms)
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800">
                    <p class="text-xs text-slate-500 mb-1">Symptoms</p>
                    <p class="text-sm text-slate-900 dark:text-white">{{ $symptoms }}</p>
                </div>
            @endif
        </div>

        <div class="flex gap-3">
            <flux:button wire:click="prevStep" variant="ghost" class="border-slate-200 dark:border-slate-700 rounded-xl">
                ← Back
            </flux:button>
            <flux:button wire:click="confirmBooking"
                         class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white border-0 rounded-xl font-semibold"
                         wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="confirmBooking">✅ Confirm Booking</span>
                <span wire:loading wire:target="confirmBooking">Booking…</span>
            </flux:button>
        </div>
        @endif

    </div>
</div>
