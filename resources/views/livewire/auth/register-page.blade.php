<div class="min-h-screen py-12 px-4 bg-slate-50 dark:bg-slate-950" x-data="{ 
    registerType: @entangle('registerType'),
    step: 1,
    loading: false,
    selectedPackage: @entangle('selected_package_id')
}" @registration-success.window="loading = true; window.location.href = $event.detail.route">
    
    <!-- Background Decor -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-teal/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brand-green/5 rounded-full blur-3xl"></div>
    </div>

    <div class="max-w-4xl mx-auto relative z-10">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-[2rem] bg-brand-teal mb-4 shadow-xl shadow-brand-teal/20 animate-in fade-in zoom-in duration-500">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Onboard with Clinix</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-bold uppercase text-[10px] tracking-widest">Healthcare Evolution Protocol</p>
        </div>

        <!-- Registration Container -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] shadow-2xl overflow-hidden">
            
            <!-- Type Selector Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-8 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                <button 
                    @click="registerType = 'clinic'; step = 1"
                    type="button"
                    :class="{ 
                        'ring-4 ring-brand-teal/10 bg-white dark:bg-slate-800 border-brand-teal shadow-xl': registerType === 'clinic',
                        'bg-slate-100 dark:bg-slate-800/50 border-transparent hover:bg-white dark:hover:bg-slate-800': registerType !== 'clinic'
                    }"
                    class="group relative flex flex-col items-center justify-center p-8 rounded-[2rem] border-2 transition-all duration-500 hover:scale-[1.02] active:scale-[0.98]">
                    
                    <div :class="registerType === 'clinic' ? 'bg-brand-teal text-white shadow-lg shadow-brand-teal/20' : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 group-hover:bg-brand-teal group-hover:text-white'"
                         class="w-20 h-20 rounded-[1.5rem] flex items-center justify-center mb-4 transition-all duration-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    
                    <h3 class="text-xl font-black mb-2 tracking-tight" :class="registerType === 'clinic' ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white'">Clinic Facility</h3>
                    <p class="text-xs font-bold uppercase tracking-wider text-center" :class="registerType === 'clinic' ? 'text-brand-teal' : 'text-slate-400 group-hover:text-brand-teal'">Enterprise Healthcare</p>
                    
                    <div x-show="registerType === 'clinic'" x-transition class="absolute -top-2 -right-2 w-8 h-8 bg-brand-teal rounded-full flex items-center justify-center shadow-lg border-4 border-white dark:border-slate-800">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </button>

                <button 
                    @click="registerType = 'patient'; step = 1"
                    type="button"
                    :class="{ 
                        'ring-4 ring-brand-green/10 bg-white dark:bg-slate-800 border-brand-green shadow-xl': registerType === 'patient',
                        'bg-slate-100 dark:bg-slate-800/50 border-transparent hover:bg-white dark:hover:bg-slate-800': registerType !== 'patient'
                    }"
                    class="group relative flex flex-col items-center justify-center p-8 rounded-[2rem] border-2 transition-all duration-500 hover:scale-[1.02] active:scale-[0.98]">
                    
                    <div :class="registerType === 'patient' ? 'bg-brand-green text-white shadow-lg shadow-brand-green/20' : 'bg-slate-200 dark:bg-slate-700 text-slate-400 dark:text-slate-500 group-hover:bg-brand-green group-hover:text-white'"
                         class="w-20 h-20 rounded-[1.5rem] flex items-center justify-center mb-4 transition-all duration-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    
                    <h3 class="text-xl font-black mb-2 tracking-tight" :class="registerType === 'patient' ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white'">Personal Care</h3>
                    <p class="text-xs font-bold uppercase tracking-wider text-center" :class="registerType === 'patient' ? 'text-brand-green' : 'text-slate-400 group-hover:text-brand-green'">Individual Patient</p>

                    <div x-show="registerType === 'patient'" x-transition class="absolute -top-2 -right-2 w-8 h-8 bg-brand-green rounded-full flex items-center justify-center shadow-lg border-4 border-white dark:border-slate-800">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </button>
            </div>

            <!-- Form Content -->
            <form wire:submit="register" class="p-8">
                
                <!-- Clinic Flow -->
                <div x-show="registerType === 'clinic'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4">
                    <!-- Step 1: Package Grid -->
                    <div x-show="step === 1" class="space-y-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-black text-brand-teal uppercase tracking-widest">Select Platform Tier</h3>
                            <span class="text-[10px] font-bold text-slate-400">STEP 1 OF 2</span>
                        </div>
                        <div class="grid md:grid-cols-3 gap-6">
                            @foreach($packages as $package)
                            <div 
                                @click="selectedPackage = '{{ $package->id }}'"
                                :class="{ 'scale-105 border-brand-teal bg-white dark:bg-slate-800 shadow-xl': selectedPackage == '{{ $package->id }}', 'bg-slate-50 dark:bg-slate-800/50 border-slate-100 dark:border-slate-800': selectedPackage != '{{ $package->id }}' }"
                                class="cursor-pointer p-6 rounded-3xl border-2 transition-all duration-500 relative group overflow-hidden">
                                
                                <div x-show="selectedPackage == '{{ $package->id }}'" class="absolute top-0 right-0 p-2">
                                    <svg class="w-5 h-5 text-brand-teal" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>

                                <h4 class="text-sm font-black text-slate-500 uppercase tracking-tighter mb-1">{{ $package->name }}</h4>
                                <div class="flex items-baseline gap-1 mb-6">
                                    <span class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">${{ $package->price }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">/Quarter</span>
                                </div>
                                
                                <ul class="space-y-3">
                                    @foreach([
                                        ['icon' => 'building', 'text' => $package->max_clinics . ' Clinic Units'],
                                        ['icon' => 'users', 'text' => $package->max_doctors . ' Practitioners'],
                                        ['icon' => 'check-circle', 'text' => $package->telemedicine ? 'Full TeleHealth' : 'Medical Core'],
                                    ] as $feature)
                                    <li class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-slate-400">
                                        <x-icons :name="$feature['icon']" class="w-4 h-4 text-brand-teal opacity-50" />
                                        {{ $feature['text'] }}
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>
                        @error('selected_package_id') <span class="text-rose-500 text-[10px] font-black uppercase tracking-wide animate-in fade-in slide-in-from-left-2 mt-2 block">{{ $message }}</span> @enderror
                        
                        <div class="flex justify-end pt-8">
                            <button type="button" @click="step = 2" class="px-8 py-3 bg-brand-teal text-white rounded-2xl font-black uppercase tracking-widest hover:shadow-xl hover:shadow-brand-teal/20 transition-all active:scale-95">Continue Onboarding</button>
                        </div>
                    </div>

                    <!-- Step 2: Clinic Core Details -->
                    <div x-show="step === 2" x-transition class="space-y-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-black text-brand-teal uppercase tracking-widest">Infrastructure Profile</h3>
                            <span class="text-[10px] font-bold text-slate-400">STEP 2 OF 2</span>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Guardian Name</label>
                                <input wire:model="name" type="text" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none" placeholder="Chief Medical Officer">
                                @error('name') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Master Email</label>
                                <input wire:model="email" type="email" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none" placeholder="admin@facility.com">
                                @error('email') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Facility Name</label>
                            <input wire:model="clinic_name" type="text" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none" placeholder="City Medical Center Hub">
                            @error('clinic_name') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Direct Line</label>
                                <input wire:model="phone" type="tel" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none" placeholder="+1 (444) 000-000">
                                @error('phone') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Facility Registry Phone</label>
                                <input wire:model="clinic_phone" type="tel" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none" placeholder="+1 (444) 111-111">
                                @error('clinic_phone') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Physical Address Registry</label>
                            <textarea wire:model="clinic_address" rows="2" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none" placeholder="Operational street details..."></textarea>
                            @error('clinic_address') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">City</label>
                                <input wire:model="clinic_city" type="text" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:border-brand-teal outline-none" placeholder="Medical Hub">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">State</label>
                                <input wire:model="clinic_state" type="text" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:border-brand-teal outline-none" placeholder="Healthcare Zone">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Country</label>
                                <input wire:model="clinic_country" type="text" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:border-brand-teal outline-none" placeholder="Global Registry">
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Access Key</label>
                                <input wire:model="password" type="password" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none" placeholder="••••••••••••">
                                @error('password') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Verify Key</label>
                                <input wire:model="password_confirmation" type="password" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none" placeholder="••••••••••••">
                            </div>
                        </div>

                        <div class="flex justify-between pt-8">
                            <button type="button" @click="step = 1" class="px-8 py-3 border border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 rounded-2xl font-black uppercase tracking-widest hover:bg-slate-50 transition-all text-[10px]">Back to Tiers</button>
                            <button type="submit" x-bind:disabled="loading" class="px-8 py-3 bg-brand-teal text-white rounded-2xl font-black uppercase tracking-widest hover:shadow-xl shadow-brand-teal/20 transition-all active:scale-95 disabled:opacity-50 flex items-center gap-3">
                                <svg x-show="loading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="loading ? 'Deploying infrastructure...' : 'Initialize Facility'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Patient Flow -->
                <div x-show="registerType === 'patient'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" class="space-y-6">
                    <div class="mb-4">
                        <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 py-4 px-4 bg-white border border-slate-200 hover:bg-slate-50 text-slate-900 font-bold rounded-[1.5rem] transition-all shadow-sm active:scale-[0.98] group">
                            <svg class="w-6 h-6" viewBox="0 0 24 24">
                                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                            </svg>
                            Onboard with Google ID
                        </a>
                        <div class="relative my-8 text-center">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100 dark:border-slate-800"></div></div>
                            <span class="relative px-6 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-white dark:bg-slate-900 leading-none tracking-[0.3em]">MANUAL REGISTRY</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-black text-brand-green uppercase tracking-widest">Medical Identity</h3>
                        <span class="text-[10px] font-bold text-slate-400 underline underline-offset-4 decoration-brand-green/30 tracking-tighter">Clinical Standard Compliance</span>
                    </div>
                    
                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Legal First Name</label>
                            <input wire:model="first_name" type="text" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-green focus:ring-4 focus:ring-brand-green/5 transition-all outline-none" placeholder="John">
                            @error('first_name') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Legal Surname</label>
                            <input wire:model="last_name" type="text" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-green focus:ring-4 focus:ring-brand-green/5 transition-all outline-none" placeholder="Doe">
                            @error('last_name') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Personal Hub Email</label>
                            <input wire:model="email" type="email" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-green focus:ring-4 focus:ring-brand-green/5 transition-all outline-none" placeholder="john.doe@email.com">
                            @error('email') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Mobile Access</label>
                            <input wire:model="phone" type="tel" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-green focus:ring-4 focus:ring-brand-green/5 transition-all outline-none" placeholder="+1 (000) 000-000">
                            @error('phone') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Birth Registry</label>
                            <input wire:model="date_of_birth" type="date" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:border-brand-green outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Bio Gender</label>
                            <select wire:model="gender" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:border-brand-green outline-none font-bold text-xs uppercase cursor-pointer">
                                <option value="">Select Category</option>
                                <option value="male">Male Spectrum</option>
                                <option value="female">Female Spectrum</option>
                                <option value="other">Diverse / Undisclosed</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Vital Group</label>
                            <select wire:model="blood_group" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white focus:border-brand-green outline-none font-bold text-xs uppercase cursor-pointer">
                                <option value="">Blood Type</option>
                                <option value="A+">A Positive</option>
                                <option value="A-">A Negative</option>
                                <option value="B+">B Positive</option>
                                <option value="B-">B Negative</option>
                                <option value="AB+">AB Positive</option>
                                <option value="AB-">AB Negative</option>
                                <option value="O+">O Positive</option>
                                <option value="O-">O Negative</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Residential Registry</label>
                        <textarea wire:model="address" rows="2" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-green focus:ring-4 focus:ring-brand-green/5 transition-all outline-none" placeholder="Home address details..."></textarea>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Primary Kin Name</label>
                            <input wire:model="emergency_contact_name" type="text" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white outline-none" placeholder="Emergency contact">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Kin Hotline</label>
                            <input wire:model="emergency_contact_phone" type="tel" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white outline-none" placeholder="Direct line">
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Access Key</label>
                            <input wire:model="password" type="password" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-green focus:ring-4 focus:ring-brand-green/5 transition-all outline-none" placeholder="••••••••••••">
                            @error('password') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Key Integrity Check</label>
                            <input wire:model="password_confirmation" type="password" class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-green focus:ring-4 focus:ring-brand-green/5 transition-all outline-none" placeholder="••••••••••••">
                        </div>
                    </div>

                    <div class="pt-8">
                        <button type="submit" x-bind:disabled="loading" class="w-full py-4 bg-brand-green text-white font-black uppercase tracking-widest rounded-2xl hover:shadow-xl shadow-brand-green/20 transition-all active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-3">
                            <svg x-show="loading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span x-text="loading ? 'Validating records...' : 'Complete Patient Boarding'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Utility Links -->
        <div class="text-center mt-8">
            <p class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest">
                Existing identity on file?
                <a href="{{ route('login') }}" class="text-brand-teal hover:text-brand-teal-dark font-black underline underline-offset-4 ml-1">Authorize Session</a>
            </p>
        </div>
    </div>
</div>