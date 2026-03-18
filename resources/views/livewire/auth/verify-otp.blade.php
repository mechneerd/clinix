<div class="min-h-screen flex items-center justify-center bg-slate-50 dark:bg-slate-950 px-4">
    <!-- Background Decor -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-teal/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-brand-green/5 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-[2rem] bg-brand-teal mb-4 shadow-xl shadow-brand-teal/20 animate-in fade-in zoom-in duration-500">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Identity Shield</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-2 font-bold uppercase text-[10px] tracking-[0.3em]">Two-Factor Registry Verification</p>
        </div>

        <!-- Verification Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-[2.5rem] p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-5">
                <svg class="w-32 h-32 text-brand-teal" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2.166 12.225 2.166 7a6.97 6.97 0 01.166-2.001zm6.32 8.606l4-4a1 1 0 00-1.42-1.42l-3.29 3.29-1.29-1.3a1 1 0 00-1.42 1.42l2 2a1 1 0 001.42 0z" clip-rule="evenodd"></path></svg>
            </div>

            <div class="mb-8 text-center relative z-10">
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-2 uppercase tracking-tighter">Enter Authentication Code</h3>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 leading-relaxed">
                    {{ __('A 6-digit cryptographic security code was dispatched to your registered medical email address.') }}
                </p>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-brand-green/10 border border-brand-green/20 text-brand-green text-[10px] font-black uppercase tracking-widest text-center animate-in fade-in slide-in-from-top-2">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="verify" class="space-y-8 relative z-10">
                <div class="space-y-4">
                    <label class="block text-[10px] font-black uppercase tracking-[0.4em] text-slate-400 text-center">Security OTP Sequence</label>
                    <div class="relative group">
                        <input 
                            id="otp" 
                            type="text" 
                            wire:model="otp" 
                            required 
                            autofocus 
                            maxlength="6"
                            class="block w-full text-center text-4xl tracking-[1.5rem] font-black py-6 bg-slate-50 dark:bg-slate-950 border-2 border-slate-100 dark:border-slate-800 rounded-3xl text-slate-900 dark:text-white focus:border-brand-teal focus:ring-8 focus:ring-brand-teal/5 transition-all outline-none"
                            placeholder="000000"
                        />
                        <div class="absolute inset-x-0 -bottom-1 h-1 bg-brand-teal/20 scale-x-0 group-focus-within:scale-x-90 transition-transform duration-500 rounded-full"></div>
                    </div>
                    @error('otp') <span class="block text-center text-rose-500 text-[10px] font-bold uppercase tracking-wide animate-in fade-in slide-in-from-bottom-2">{{ $message }}</span> @enderror
                </div>

                <div class="space-y-4">
                    <button type="submit" class="w-full py-4 bg-brand-teal text-white font-black uppercase tracking-widest rounded-2xl hover:shadow-xl hover:shadow-brand-teal/20 transition-all hover:scale-[1.01] active:scale-[0.98] flex items-center justify-center gap-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Validate Identity
                    </button>

                    <div class="flex items-center justify-between px-2 pt-2">
                        <button type="button" wire:click="resend" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-brand-teal transition-colors flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Dispatch New Key
                        </button>
                        
                        <button type="button" wire:click="logout" class="text-[10px] font-black uppercase tracking-widest text-rose-400 hover:text-rose-600 transition-colors flex items-center gap-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Terminate
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="text-center mt-10">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">Secured by Clinix Protocol Layer</p>
        </div>
    </div>
</div>
