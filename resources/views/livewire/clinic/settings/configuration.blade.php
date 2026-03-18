<div class="space-y-8 pb-20">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">System Configuration</h2>
            <p class="text-slate-500">Fine-tune your clinic's operational parameters and preferences.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Settings Panel -->
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">Operational Defaults</h3>
                </div>

                <div class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($settings as $index => $setting)
                    <div class="p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 group">
                        <div class="flex-1">
                            <span class="text-xs font-black text-brand-teal uppercase tracking-widest block mb-1">{{ str_replace('_', ' ', $setting['key']) }}</span>
                            <p class="text-slate-500 text-sm">System key: <span class="font-mono text-[10px]">{{ $setting['key'] }}</span></p>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            @if($setting['type'] === 'boolean')
                                <button 
                                    wire:click="updateSetting({{ $setting['id'] }}, '{{ $setting['value'] === 'true' ? 'false' : 'true' }}')"
                                    class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors {{ $setting['value'] === 'true' ? 'bg-brand-teal' : 'bg-slate-200 dark:bg-slate-700' }}"
                                >
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform {{ $setting['value'] === 'true' ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            @else
                                <input 
                                    type="text" 
                                    value="{{ $setting['value'] }}"
                                    wire:blur="updateSetting({{ $setting['id'] }}, $event.target.value)"
                                    class="h-10 bg-slate-50 dark:bg-slate-800 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-brand-teal min-w-[150px]"
                                >
                            @endif
                            
                            <button wire:click="deleteSetting({{ $setting['id'] }})" class="p-2 text-slate-300 hover:text-rose-500 transition-colors opacity-0 group-hover:opacity-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Add New Setting Panel -->
        <div class="lg:col-span-4">
            <div class="bg-brand-teal text-white rounded-[2.5rem] p-8 shadow-xl shadow-brand-teal/20 relative overflow-hidden">
                <!-- Decorative Circle -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                
                <h3 class="text-xl font-bold mb-6 relative z-10">Add Custom Setting</h3>
                
                <div class="space-y-4 relative z-10">
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest opacity-70">Setting Key</label>
                        <input type="text" wire:model="newKey" placeholder="eg. sms_template" class="w-full h-12 bg-white/20 border-white/20 rounded-2xl px-4 text-white placeholder-white/50 focus:bg-white/30 focus:ring-0 transition-all border">
                        @error('newKey') <span class="text-[10px] text-rose-200">{{ $message }}</span> @enderror
                    </div>
                    
                    <div class="space-y-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest opacity-70">Initial Value</label>
                        <textarea wire:model="newValue" rows="3" placeholder="Enter value..." class="w-full bg-white/20 border-white/20 rounded-2xl px-4 py-3 text-white placeholder-white/50 focus:bg-white/30 focus:ring-0 transition-all border"></textarea>
                        @error('newValue') <span class="text-[10px] text-rose-200">{{ $message }}</span> @enderror
                    </div>

                    <button wire:click="addSetting" class="w-full py-4 bg-white text-brand-teal rounded-2xl font-black shadow-lg hover:scale-[1.02] transition-all">
                        Create Option
                    </button>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-8 p-8 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem]">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-500/10 flex items-center justify-center text-blue-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800 dark:text-white">Pro Tip</h4>
                </div>
                <p class="text-sm text-slate-500 leading-relaxed">
                    These settings are used by the system to customize your clinic's behavior. For example, changing the <span class="font-bold text-brand-teal">currency</span> will update all invoices automatically.
                </p>
            </div>
        </div>
    </div>
</div>
