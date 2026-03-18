<div class="space-y-6">
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 mb-8">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-violet-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative">
            <h1 class="text-3xl font-bold text-white mb-2">{{ $pageTitle }}</h1>
            <p class="text-slate-400">Configure your clinic's identity and contact information</p>
        </div>
    </div>

    <!-- Setup Form -->
    <form wire:submit="save" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Logo & Branding Section -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 sticky top-24">
                <h3 class="text-lg font-bold text-white mb-6">Clinic Branding</h3>
                
                <div class="flex flex-col items-center">
                    <div class="relative group cursor-pointer">
                        <div class="w-32 h-32 rounded-3xl bg-slate-800 border-2 border-dashed border-slate-700 flex items-center justify-center overflow-hidden transition-all group-hover:border-cyan-500/50">
                            @if ($logo)
                                <img src="{{ $logo->temporaryUrl() }}" class="w-full h-full object-cover">
                            @elseif ($existingLogo)
                                <img src="{{ asset('storage/' . $existingLogo) }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-slate-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs text-slate-500">Upload Logo</span>
                                </div>
                            @endif
                        </div>
                        <input type="file" wire:model="logo" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>
                    <p class="text-xs text-slate-500 mt-4 text-center">Recommended size: 512x512px. Max 2MB.</p>
                    @error('logo') <span class="text-xs text-rose-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="mt-8 pt-8 border-t border-slate-800">
                    <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-700">
                        <p class="text-xs text-slate-400 leading-relaxed italic">
                            "Setting up your clinic profile correctly ensures accurate billing, professionalism in patient reports, and clear communication."
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Details Section -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8">
                <h3 class="text-lg font-bold text-white mb-8 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </span>
                    Basic Information
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">Clinic Name</label>
                        <input type="text" wire:model="name" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="Enter clinic name">
                        @error('name') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="Tell patients about your clinic..."></textarea>
                        @error('description') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">Email Address</label>
                        <input type="email" wire:model="email" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="clinic@example.com">
                        @error('email') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">Phone Number</label>
                        <input type="text" wire:model="phone" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="+1 (555) 000-0000">
                        @error('phone') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <h3 class="text-lg font-bold text-white mt-12 mb-8 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-violet-500/10 flex items-center justify-center">
                        <svg class="w-4 h-4 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </span>
                    Location Details
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">Full Address</label>
                        <input type="text" wire:model="address" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="123 Medical St, Suite 100">
                        @error('address') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">City</label>
                        <input type="text" wire:model="city" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="City">
                        @error('city') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">State / Province</label>
                        <input type="text" wire:model="state" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="State">
                        @error('state') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">Country</label>
                        <input type="text" wire:model="country" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="Country">
                        @error('country') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-12 flex justify-end">
                    <button type="submit" class="px-10 py-4 bg-gradient-to-r from-cyan-600 to-violet-600 hover:from-cyan-500 hover:to-violet-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-cyan-500/20 active:scale-95 flex items-center gap-3">
                        <span wire:loading.remove>Save changes</span>
                        <span wire:loading>Saving...</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
