<div class="font-['Inter'] antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/80 dark:bg-slate-950/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center gap-3 group">
                    <div class="w-10 h-10 bg-brand-teal rounded-xl flex items-center justify-center shadow-lg shadow-brand-teal/20 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Clinix</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-10">
                    <div class="flex items-center gap-8">
                        <a href="#features" class="text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 hover:text-brand-teal transition-colors">Features</a>
                        <a href="#departments" class="text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 hover:text-brand-teal transition-colors">Specialties</a>
                        <a href="{{ route('login') }}" class="text-xs font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 hover:text-brand-teal transition-colors" wire:navigate.hover>Authorize</a>
                    </div>
                    <a href="{{ route('register') }}" class="px-7 py-3 bg-brand-teal text-white rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-xl shadow-brand-teal/20 hover:scale-[1.05] active:scale-[0.98] transition-all" wire:navigate.hover>
                        Initialize
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden">
        <!-- Abstract Decorations -->
        <div class="absolute inset-0 pointer-events-none">
            <div class="absolute top-[10%] left-[10%] w-[30rem] h-[30rem] bg-brand-teal/5 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[35rem] h-[35rem] bg-brand-green/5 rounded-full blur-[150px]"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-20 items-center">
                <div class="space-y-10 animate-in fade-in slide-in-from-left-8 duration-700">
                    <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-2xl bg-brand-teal/10 border border-brand-teal/20 text-brand-teal text-xs font-black uppercase tracking-widest">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-teal opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-teal"></span>
                        </span>
                        Next-Gen Care Delivery 2026
                    </div>
                    
                    <h1 class="text-6xl md:text-8xl font-black leading-[0.9] tracking-tighter text-slate-900 dark:text-white">
                        Care & <br>
                        <span class="text-brand-teal">Clarity.</span>
                    </h1>
                    
                    <p class="text-lg font-medium text-slate-500 dark:text-slate-400 max-w-lg leading-relaxed">
                        The unified platform for modern clinics and their patients. Seamlessly manage operations while delivering an exceptional healthcare experience.
                    </p>
                    
                    <div class="flex flex-wrap gap-5">
                        <a href="{{ route('register') }}" class="px-10 py-5 bg-brand-teal text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-brand-teal/30 hover:shadow-brand-teal/40 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center gap-3" wire:navigate.hover>
                            Join Now
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                        <a href="#features" class="px-10 py-5 bg-white dark:bg-slate-900 text-slate-900 dark:text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] border border-slate-200 dark:border-slate-800 hover:bg-slate-50 transition-all flex items-center gap-3 shadow-sm">
                            Explore
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-10 pt-10 border-t border-slate-100 dark:border-slate-800">
                        <div>
                            <div class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">500k+</div>
                            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mt-1">Patient Encounters</div>
                        </div>
                        <div>
                            <div class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">1.2k</div>
                            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mt-1">Healthcare Providers</div>
                        </div>
                        <div>
                            <div class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">4.9/5</div>
                            <div class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mt-1">Patient Rating</div>
                        </div>
                    </div>
                </div>

                <!-- Hero Identity -->
                <div class="hidden lg:block relative animate-in fade-in zoom-in duration-1000">
                    <div class="relative bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[3rem] p-10 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.1)]">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-slate-100 dark:bg-slate-800"></div>
                                <div class="w-3 h-3 rounded-full bg-slate-100 dark:bg-slate-800"></div>
                                <div class="w-3 h-3 rounded-full bg-brand-teal/20"></div>
                            </div>
                            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Live Pulse</span>
                        </div>
                        <div class="space-y-8">
                            <div class="grid grid-cols-2 gap-6">
                                <div class="bg-slate-50 dark:bg-slate-950 rounded-[2rem] p-6 border border-slate-100 dark:border-slate-800">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Patient Satisfaction</p>
                                    <div class="text-3xl font-black text-brand-teal tracking-tighter">98.4%</div>
                                </div>
                                <div class="bg-brand-teal rounded-[2rem] p-6 text-white shadow-xl shadow-brand-teal/20">
                                    <p class="text-[10px] font-black text-brand-teal-light uppercase tracking-widest mb-2 opacity-80">Daily Care</p>
                                    <div class="text-3xl font-black tracking-tighter">142</div>
                                </div>
                            </div>
                            <!-- Mock UI element -->
                            <div class="space-y-4">
                                <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800">
                                    <div class="w-12 h-12 rounded-xl bg-brand-teal/10 flex items-center justify-center">
                                        <x-icons name="patient" class="w-6 h-6 text-brand-teal" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-black text-slate-900 dark:text-white">Alex Johnson</div>
                                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Check-in at 09:45 AM</div>
                                    </div>
                                    <div class="px-3 py-1 bg-brand-teal/10 text-brand-teal text-[10px] font-black rounded-lg">NEW</div>
                                </div>
                                <div class="flex items-center gap-4 p-5 bg-slate-50 dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800">
                                    <div class="w-12 h-12 rounded-xl bg-brand-green/10 flex items-center justify-center">
                                        <x-icons name="calendar" class="w-6 h-6 text-brand-green" />
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-sm font-black text-slate-900 dark:text-white">Dermatology Consult</div>
                                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Confirmed • 11:30 AM</div>
                                    </div>
                                    <div class="px-3 py-1 bg-brand-green/10 text-brand-green text-[10px] font-black rounded-lg">READY</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Accent element -->
                    <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-brand-green/20 rounded-3xl blur-2xl -z-10 animate-pulse"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Logic Section -->
    <section id="features" class="py-32 bg-white dark:bg-slate-950 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-24">
                <h2 class="text-sm font-black text-brand-teal uppercase tracking-[0.5em] mb-4">Patient-First Platform</h2>
                <h3 class="text-5xl font-black text-slate-900 dark:text-white tracking-tighter">Built for Better Care</h3>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-10">
                @php
                $features = [
                    ['icon' => 'calendar', 'title' => 'Smart Scheduling', 'desc' => 'Easy online booking and automated reminders to keep your healthcare on track.', 'color' => 'brand-teal'],
                    ['icon' => 'users', 'title' => 'Patient Portal', 'desc' => 'Personal medical records, prescription history, and lab results at your fingertips.', 'color' => 'brand-green'],
                    ['icon' => 'document', 'title' => 'Telehealth Ready', 'desc' => 'Modern virtual consultation infrastructure for care that meets you anywhere.', 'color' => 'brand-teal'],
                    ['icon' => 'credit-card', 'title' => 'Simple Billing', 'desc' => 'Transparent invoices and secure digital payments for a stress-free experience.', 'color' => 'brand-green'],
                    ['icon' => 'chart', 'title' => 'Health Analytics', 'desc' => 'Insights for providers to optimize care pathways and improve patient outcomes.', 'color' => 'brand-teal'],
                    ['icon' => 'shield', 'title' => 'Privacy Core', 'desc' => 'Military-grade encryption for HIPAA-compliant data protection and total peace of mind.', 'color' => 'brand-green'],
                ];
                @endphp

                @foreach($features as $feature)
                <div class="group p-8 rounded-[2.5rem] bg-slate-50 dark:bg-slate-900 border border-slate-100 dark:border-slate-800 hover:border-{{ $feature['color'] }}/30 transition-all duration-500 hover:shadow-2xl hover:shadow-{{ $feature['color'] }}/10">
                    <div class="w-14 h-14 bg-white dark:bg-slate-800 rounded-2xl flex items-center justify-center mb-8 shadow-sm group-hover:scale-110 transition-transform border border-slate-100 dark:border-slate-700">
                        <x-icons :name="$feature['icon']" class="w-7 h-7 text-{{ $feature['color'] }}" />
                    </div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">{{ $feature['title'] }}</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm font-medium leading-relaxed">{{ $feature['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Platform CTA -->
    <section class="py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-brand-teal/5 dark:bg-slate-900/50"></div>
        <div class="max-w-4xl mx-auto px-4 text-center relative z-10">
            <h2 class="text-5xl md:text-6xl font-black text-slate-900 dark:text-white mb-10 tracking-tighter">Ready for Better Healthcare?</h2>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <a href="{{ route('register') }}" class="px-12 py-5 bg-brand-teal text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] shadow-2xl shadow-brand-teal/20 hover:scale-[1.05] transition-all" wire:navigate.hover>
                    Get Started Today
                </a>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Available for Clinics & Patients • Free to Join</span>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white dark:bg-slate-950 border-t border-slate-100 dark:border-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-10">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-brand-teal rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">Clinix</span>
                </div>
                <div class="flex items-center gap-10">
                    <a href="#" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-teal transition-colors">Privacy</a>
                    <a href="#" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-teal transition-colors">Security</a>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-300 dark:text-slate-600">&copy; 2026 CLINIX.CORE</span>
                </div>
            </div>
        </div>
    </footer>

</div>