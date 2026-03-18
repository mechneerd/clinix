<div class="space-y-6">
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 mb-8">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-64 h-64 bg-violet-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ $pageTitle }}</h1>
                <p class="text-slate-400">Choose the perfect plan to grow your medical practice</p>
            </div>
            @if($currentClinic && $currentClinic->package)
                <div class="flex items-center gap-3 px-6 py-3 bg-cyan-500/10 border border-cyan-500/20 rounded-2xl">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/20 flex items-center justify-center">
                        <x-icons name="package" class="w-6 h-6 text-cyan-400" />
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-bold tracking-wider">Current Plan</p>
                        <p class="text-white font-bold">{{ $currentClinic->package->name }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Pricing Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($packages as $package)
            @php
                $isCurrent = $currentClinic && $currentClinic->package_id == $package->id;
            @endphp
            <div class="relative group">
                <div class="absolute -inset-0.5 bg-gradient-to-r {{ $isCurrent ? 'from-cyan-500 to-violet-600' : 'from-slate-700 to-slate-800' }} rounded-[2rem] blur opacity-25 group-hover:opacity-50 transition duration-1000 group-hover:duration-200"></div>
                <div class="relative flex flex-col h-full bg-slate-900 border {{ $isCurrent ? 'border-cyan-500/30' : 'border-slate-800' }} rounded-[2rem] p-8 overflow-hidden">
                    
                    @if($isCurrent)
                        <div class="absolute top-0 right-0 bg-gradient-to-l from-cyan-500 to-violet-600 text-white text-[10px] font-bold px-4 py-1.5 rounded-bl-2xl uppercase tracking-widest shadow-lg">Active Plan</div>
                    @endif

                    <!-- Plan Header -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-white mb-2">{{ $package->name }}</h3>
                        <div class="flex items-baseline gap-1 mb-4">
                            <span class="text-4xl font-black text-white">${{ number_format($package->price, 0) }}</span>
                            <span class="text-slate-500">/{{ $package->billing_cycle }}</span>
                        </div>
                        <p class="text-slate-400 text-sm line-clamp-2 leading-relaxed h-10">{{ $package->description }}</p>
                    </div>

                    <!-- Features List -->
                    <div class="flex-1 space-y-4 mb-8">
                        <div class="flex items-center gap-3 text-slate-300 text-sm">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-cyan-500/10 flex items-center justify-center">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Up to <strong>{{ $package->max_doctors }}</strong> Doctors</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-300 text-sm">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-cyan-500/10 flex items-center justify-center">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Up to <strong>{{ $package->max_staff }}</strong> Staff Members</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-300 text-sm">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-cyan-500/10 flex items-center justify-center">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span><strong>{{ $package->max_patients_per_month ?? 'Unlimited' }}</strong> Patients / Month</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-300 text-sm">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-cyan-500/10 flex items-center justify-center">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span><strong>{{ $package->storage_limit_mb / 1024 }}GB</strong> Cloud Storage</span>
                        </div>
                        @if($package->telemedicine)
                        <div class="flex items-center gap-3 text-slate-300 text-sm">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-cyan-500/10 flex items-center justify-center">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>Telemedicine Module</span>
                        </div>
                        @endif
                        @if($package->white_label)
                        <div class="flex items-center gap-3 text-slate-300 text-sm">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-cyan-500/10 flex items-center justify-center">
                                <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span>White Label Customization</span>
                        </div>
                        @endif
                    </div>

                    <!-- Action Button -->
                    @if($isCurrent)
                        <button disabled class="w-full py-4 px-6 bg-slate-800 text-slate-500 rounded-2xl font-bold cursor-not-allowed border border-slate-700">Currently Active</button>
                    @else
                        <button 
                            wire:click="buyPackage({{ $package->id }})"
                            wire:loading.attr="disabled"
                            class="w-full py-4 px-6 bg-gradient-to-r from-cyan-600 to-violet-600 hover:from-cyan-500 hover:to-violet-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-cyan-500/20 active:scale-95 flex items-center justify-center gap-2 group/btn"
                        >
                            <span wire:loading.remove>Buy Now</span>
                            <span wire:loading>Processing...</span>
                            <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
