<div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-slate-950 px-4" x-data="{ 
    loginType: @entangle('loginType'),
    userType: @entangle('userType'),
    loading: false
}" x-on:livewire:loading.window="loading = true">
    
    <!-- Background Decor -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-teal/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brand-green/5 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-[2rem] bg-brand-teal mb-4 shadow-xl shadow-brand-teal/20 animate-in fade-in zoom-in duration-500">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Clinix</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-bold uppercase text-[10px] tracking-widest">Medical Management System</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-2xl">
            
            <!-- Login Type Selector -->
            <div class="relative flex p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl mb-8 border border-slate-200 dark:border-slate-700">
                <!-- Sliding Background -->
                <div 
                    class="absolute inset-y-1 transition-all duration-300 ease-out rounded-xl bg-brand-teal shadow-lg shadow-brand-teal/20"
                    :class="{ 
                        'left-1 w-[calc(50%-0.25rem)]': loginType === 'clinic',
                        'left-[calc(50%+0.125rem)] w-[calc(50%-0.25rem)]': loginType === 'patient'
                    }">
                </div>

                <button 
                    @click="loginType = 'clinic'; userType = 'admin'"
                    type="button"
                    class="relative z-10 flex-1 py-3 px-4 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 group"
                    :class="loginType === 'clinic' ? 'text-white' : 'text-slate-500 dark:text-slate-400 hover:text-brand-teal'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    Clinic
                </button>
                <button 
                    @click="loginType = 'patient'"
                    type="button"
                    class="relative z-10 flex-1 py-3 px-4 rounded-xl text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 group"
                    :class="loginType === 'patient' ? 'text-white' : 'text-slate-500 dark:text-slate-400 hover:text-brand-teal'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Patient
                </button>
            </div>

            <!-- Role Tabs (Only for Clinic login) -->
            <div x-show="loginType === 'clinic'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-2" class="flex gap-2 mb-8 p-1 bg-slate-50 dark:bg-slate-950 rounded-xl border border-slate-100 dark:border-slate-800">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" wire:model="userType" value="admin" class="sr-only peer">
                    <div class="py-2 text-center text-[10px] font-black uppercase tracking-tighter rounded-lg border border-transparent transition-all peer-checked:bg-white dark:peer-checked:bg-slate-800 peer-checked:text-brand-teal peer-checked:shadow-sm peer-checked:border-slate-100 dark:peer-checked:border-slate-700 text-slate-400">
                        Admin Access
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" wire:model="userType" value="staff" class="sr-only peer">
                    <div class="py-2 text-center text-[10px] font-black uppercase tracking-tighter rounded-lg border border-transparent transition-all peer-checked:bg-white dark:peer-checked:bg-slate-800 peer-checked:text-brand-teal peer-checked:shadow-sm peer-checked:border-slate-100 dark:peer-checked:border-slate-700 text-slate-400">
                        Medical Staff
                    </div>
                </label>
            </div>

            <!-- Authentication Form -->
            <form wire:submit="login" class="space-y-5">
                <div x-show="loginType === 'patient'" class="mb-5" x-transition>
                    <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 py-3 px-4 bg-white border border-slate-200 hover:bg-slate-50 text-slate-900 font-bold rounded-2xl transition-all shadow-sm active:scale-[0.98] group">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                        </svg>
                        Sign in with Google
                    </a>
                    <div class="relative my-6 text-center">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-slate-100 dark:border-slate-800"></div></div>
                        <span class="relative px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 bg-white dark:bg-slate-900 leading-none">Personal Account</span>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Email Hub</label>
                    <input 
                        wire:model="email" 
                        type="email" 
                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none"
                        placeholder="doctor@clinix.com">
                    @error('email') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Access Key</label>
                    <input 
                        wire:model="password" 
                        type="password" 
                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-2xl text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-teal focus:ring-4 focus:ring-brand-teal/5 transition-all outline-none"
                        placeholder="••••••••••••">
                    @error('password') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-wide ml-1 animate-in fade-in slide-in-from-left-2">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center cursor-pointer group">
                        <input wire:model="remember" type="checkbox" class="w-4 h-4 rounded-lg border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-brand-teal focus:ring-brand-teal/20 transition-all pointer-events-none">
                        <span class="ml-2 text-xs font-bold text-slate-500 dark:text-slate-400 group-hover:text-brand-teal transition-colors">Keep me signed in</span>
                    </label>
                </div>

                <button 
                    type="submit" 
                    x-bind:disabled="loading"
                    class="w-full py-4 bg-brand-teal text-white font-black uppercase tracking-widest rounded-2xl hover:shadow-xl hover:shadow-brand-teal/20 transition-all hover:scale-[1.01] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-3">
                    <svg x-show="loading" class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    <span x-text="loading ? 'Authenticating...' : 'Secure Access'"></span>
                </button>
            </form>

            <!-- Secondary Actions -->
            <div class="mt-8 pt-8 border-t border-slate-100 dark:border-slate-800 text-center">
                <p class="text-slate-500 dark:text-slate-400 text-xs font-medium">
                    New to the platform?
                    <a href="{{ route('register') }}" class="text-brand-teal hover:text-brand-teal-dark font-black uppercase tracking-tighter">Initialize Account</a>
                </p>
            </div>
        </div>

        <!-- Utility Links -->
        <div class="flex justify-center mt-8">
            <a href="{{ route('home') }}" class="text-slate-400 hover:text-brand-teal text-[10px] font-black uppercase tracking-[0.2em] flex items-center gap-2 transition-all group">
                <svg class="w-3 h-3 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Return to Landing
            </a>
        </div>
    </div>
</div>