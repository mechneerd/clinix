<div class="p-8">

    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-white">Create your account</h1>
        <p class="text-slate-400 text-sm mt-1">Select how you'll be using Clinix</p>
    </div>

    <div class="space-y-4">

        {{-- Healthcare Provider --}}
        <a href="{{ route('register.admin') }}" wire:navigate
           class="group block p-5 rounded-2xl border border-white/10 bg-white/5 hover:bg-indigo-500/10 hover:border-indigo-500/50 transition-all cursor-pointer">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-lg shadow-indigo-500/20 group-hover:shadow-indigo-500/40 transition-shadow">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="text-white font-semibold text-base">Healthcare Provider</h3>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 font-medium">Admin</span>
                    </div>
                    <p class="text-slate-400 text-sm mt-0.5">Manage clinics, labs, pharmacies, staff and patients</p>
                    <div class="flex gap-3 mt-2">
                        <span class="text-xs text-slate-500">✦ Create clinics</span>
                        <span class="text-xs text-slate-500">✦ Manage staff</span>
                        <span class="text-xs text-slate-500">✦ Full analytics</span>
                    </div>
                </div>
                <svg class="w-5 h-5 text-slate-600 group-hover:text-indigo-400 group-hover:translate-x-1 transition-all flex-shrink-0"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        {{-- Patient --}}
        <a href="{{ route('register.patient') }}" wire:navigate
           class="group block p-5 rounded-2xl border border-white/10 bg-white/5 hover:bg-emerald-500/10 hover:border-emerald-500/50 transition-all cursor-pointer">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20 group-hover:shadow-emerald-500/40 transition-shadow">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <h3 class="text-white font-semibold text-base">Patient</h3>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-medium">Free</span>
                    </div>
                    <p class="text-slate-400 text-sm mt-0.5">Book appointments, view records and manage your health</p>
                    <div class="flex gap-3 mt-2">
                        <span class="text-xs text-slate-500">✦ Book appointments</span>
                        <span class="text-xs text-slate-500">✦ Health records</span>
                    </div>
                </div>
                <svg class="w-5 h-5 text-slate-600 group-hover:text-emerald-400 group-hover:translate-x-1 transition-all flex-shrink-0"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

    </div>

    <p class="text-center text-sm text-slate-400 mt-6">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="text-indigo-400 hover:text-indigo-300 font-medium">Sign in</a>
    </p>

</div>
