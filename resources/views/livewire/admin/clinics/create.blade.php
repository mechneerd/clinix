<div class="p-6 lg:p-8 max-w-4xl mx-auto">

    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.clinics.index') }}" wire:navigate class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center hover:bg-slate-200 transition-colors">
            <flux:icon name="arrow-left" class="w-4 h-4 text-slate-600 dark:text-slate-300" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Create New Clinic</h1>
            <p class="text-slate-500 text-sm mt-0.5">Fill in the details to set up your clinic</p>
        </div>
    </div>

    <div class="space-y-6">

        {{-- Basic Information --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
            <h2 class="font-semibold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center">
                    <flux:icon name="information-circle" class="w-4 h-4 text-indigo-600" />
                </div>
                Basic Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Clinic Name *</flux:label>
                    <flux:input wire:model="name" placeholder="e.g. City Health Clinic" class="mt-1 w-full rounded-xl" />
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="md:col-span-2">
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Description</flux:label>
                    <flux:textarea wire:model="description" rows="3" placeholder="Brief description of your clinic…" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Email</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="clinic@example.com" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Phone *</flux:label>
                    <flux:input wire:model="phone" type="tel" placeholder="+1 234 567 8900" class="mt-1 w-full rounded-xl" />
                    @error('phone') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Alternate Phone</flux:label>
                    <flux:input wire:model="alternate_phone" type="tel" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Website</flux:label>
                    <flux:input wire:model="website" placeholder="https://example.com" class="mt-1 w-full rounded-xl" />
                </div>
            </div>
        </div>

        {{-- Location --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
            <h2 class="font-semibold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/40 flex items-center justify-center">
                    <flux:icon name="map-pin" class="w-4 h-4 text-emerald-600" />
                </div>
                Location
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Address *</flux:label>
                    <flux:textarea wire:model="address" rows="2" placeholder="Street address…" class="mt-1 w-full rounded-xl" />
                    @error('address') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">City *</flux:label>
                    <flux:input wire:model="city" class="mt-1 w-full rounded-xl" />
                    @error('city') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">State *</flux:label>
                    <flux:input wire:model="state" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Country *</flux:label>
                    <flux:input wire:model="country" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Postal Code</flux:label>
                    <flux:input wire:model="postal_code" class="mt-1 w-full rounded-xl" />
                </div>
            </div>
        </div>

        {{-- Working Hours --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
            <h2 class="font-semibold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-900/40 flex items-center justify-center">
                    <flux:icon name="clock" class="w-4 h-4 text-amber-600" />
                </div>
                Working Hours
            </h2>
            <div class="space-y-3">
                @foreach (['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                    <div class="flex items-center gap-4">
                        <div class="w-28 flex items-center gap-2">
                            <input type="checkbox" wire:model.live="working_hours.{{ $day }}.open"
                                   class="w-4 h-4 rounded text-indigo-600"
                                   id="day-{{ $day }}" />
                            <label for="day-{{ $day }}" class="text-sm font-medium capitalize text-slate-700 dark:text-slate-300">{{ $day }}</label>
                        </div>
                        @if ($working_hours[$day]['open'])
                            <input type="time" wire:model="working_hours.{{ $day }}.start"
                                   class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-900 dark:text-white" />
                            <span class="text-slate-500 text-sm">to</span>
                            <input type="time" wire:model="working_hours.{{ $day }}.end"
                                   class="px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-sm text-slate-900 dark:text-white" />
                        @else
                            <span class="text-sm text-slate-400 italic">Closed</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Settings & Branding --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
            <h2 class="font-semibold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-violet-100 dark:bg-violet-900/40 flex items-center justify-center">
                    <flux:icon name="paint-brush" class="w-4 h-4 text-violet-600" />
                </div>
                Settings & Branding
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Appointment Duration (minutes)</flux:label>
                    <flux:select wire:model="appointment_duration" class="mt-1 w-full rounded-xl">
                        @foreach ([10,15,20,30,45,60,90,120] as $min)
                            <option value="{{ $min }}">{{ $min }} min</option>
                        @endforeach
                    </flux:select>
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Logo</flux:label>
                    <input wire:model="logo" type="file" accept="image/*"
                           class="mt-1 w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-700" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Primary Color</flux:label>
                    <div class="mt-1 flex items-center gap-2">
                        <input type="color" wire:model="primary_color" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer" />
                        <flux:input wire:model="primary_color" class="flex-1 rounded-xl font-mono" />
                    </div>
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Secondary Color</flux:label>
                    <div class="mt-1 flex items-center gap-2">
                        <input type="color" wire:model="secondary_color" class="w-10 h-10 rounded-lg border border-slate-200 cursor-pointer" />
                        <flux:input wire:model="secondary_color" class="flex-1 rounded-xl font-mono" />
                    </div>
                </div>
                <div class="md:col-span-2 flex items-center gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-800">
                    <input type="checkbox" wire:model="show_on_public_listing" class="w-4 h-4 rounded text-indigo-600" id="public-listing" />
                    <label for="public-listing" class="text-sm text-slate-700 dark:text-slate-300">
                        Show this clinic on public listing (patients can find and book)
                    </label>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3 pb-4">
            <a href="{{ route('admin.clinics.index') }}" wire:navigate class="flex-1">
                <flux:button variant="ghost" class="w-full border-slate-200 dark:border-slate-700 rounded-xl">Cancel</flux:button>
            </a>
            <flux:button wire:click="save" wire:loading.attr="disabled"
                         class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white border-0 rounded-xl font-semibold">
                <span wire:loading.remove wire:target="save">🏥 Create Clinic</span>
                <span wire:loading wire:target="save">Creating…</span>
            </flux:button>
        </div>

    </div>
</div>
