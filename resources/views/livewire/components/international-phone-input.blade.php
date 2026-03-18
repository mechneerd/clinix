<div class="relative w-full">
    @if($label)
        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">{{ $label }}</label>
    @endif

    <div class="flex items-center gap-2">
        <!-- Country Selector -->
        <div x-data="{ open: false }" class="relative shrink-0">
            <button @click="open = !open" type="button" class="flex items-center gap-2 h-12 px-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border-none focus:ring-2 focus:ring-brand-teal transition-all min-w-[100px]">
                <span class="text-xl">{{ $selectedCountry->flag ?? '🏳️' }}</span>
                <span class="text-xs font-bold text-slate-600 dark:text-slate-300">{{ $selectedCountry->phone_code ?? '+??' }}</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
            </button>

            <!-- Dropdown -->
            <div x-show="open" @click.away="open = false" 
                 class="absolute left-0 mt-2 w-64 max-h-60 overflow-y-auto bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl z-[150] p-2 animate-in fade-in zoom-in-95 duration-200"
                 style="display: none;">
                <div class="sticky top-0 bg-white dark:bg-slate-900 pb-2 mb-2 border-b border-slate-100 dark:border-slate-800">
                    <input type="text" placeholder="Search country..." class="w-full text-xs p-2 bg-slate-50 dark:bg-slate-800 rounded-xl border-none focus:ring-1 focus:ring-brand-teal">
                </div>
                @foreach($countries as $country)
                    <button type="button" 
                            wire:click="selectCountry({{ $country->id }})" 
                            @click="open = false"
                            class="w-full flex items-center justify-between p-3 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-all group">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">{{ $country->flag }}</span>
                            <div class="text-left">
                                <p class="text-xs font-bold text-slate-800 dark:text-white capitalize">{{ $country->name }}</p>
                                <p class="text-[10px] text-slate-400 uppercase">{{ $country->iso_code }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-black text-brand-teal opacity-0 group-hover:opacity-100 transition-opacity">{{ $country->phone_code }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Phone Input -->
        <div class="relative flex-1">
            <input type="text" 
                   wire:model.live="phone"
                   placeholder="{{ $placeholder }}"
                   maxlength="{{ $selectedCountry->phone_digits ?? 15 }}"
                   class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-5 focus:ring-2 focus:ring-brand-teal transition-all text-sm font-medium"
                   oninput="this.value = this.value.replace(/[^0-9]/g, '');">
            
            @if($selectedCountry)
                <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1 pointer-events-none">
                     <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Digits:</span>
                     <span class="text-[9px] font-black text-brand-teal">{{ strlen($phone) }}/{{ $selectedCountry->phone_digits }}</span>
                </div>
            @endif
        </div>
    </div>

    @error('phone') <span class="text-[10px] text-rose-500 font-bold mt-1 block px-2">{{ $message }}</span> @enderror
</div>
