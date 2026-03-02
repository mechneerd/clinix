<div class="p-8">

    {{-- Header --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-white">Welcome back</h1>
        <p class="text-slate-400 text-sm mt-1">Sign in to your Clinix account</p>
    </div>

    {{-- Social Login --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <a href="{{ route('auth.google') }}"
           class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/80 text-sm font-medium hover:bg-white/10 transition-all">
            <svg class="w-4 h-4" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Google
        </a>
        <button class="flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-white/5 border border-white/10 text-white/80 text-sm font-medium hover:bg-white/10 transition-all">
            <svg class="w-4 h-4 fill-white" viewBox="0 0 24 24">
                <path d="M12 0C5.373 0 0 5.373 0 12c0 5.302 3.438 9.8 8.205 11.387.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 21.795 24 17.298 24 12c0-6.627-5.373-12-12-12"/>
            </svg>
            GitHub
        </button>
    </div>

    {{-- Divider --}}
    <div class="relative mb-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-white/10"></div>
        </div>
        <div class="relative flex justify-center text-xs">
            <span class="bg-transparent px-3 text-slate-500">or continue with email</span>
        </div>
    </div>

    {{-- Form --}}
    <form wire:submit="login" class="space-y-4">

        <div>
            <flux:label class="text-slate-300 text-sm">Email address</flux:label>
            <flux:input
                wire:model="email"
                type="email"
                placeholder="you@example.com"
                class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500/20 rounded-xl"
                autofocus
            />
            @error('email')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-1">
                <flux:label class="text-slate-300 text-sm">Password</flux:label>
                <a href="#"
                   class="text-xs text-indigo-400 hover:text-indigo-300">Forgot password?</a>
            </div>
            <flux:input
                wire:model="password"
                type="password"
                placeholder="••••••••"
                class="w-full bg-white/5 border-white/10 text-white placeholder-slate-500 focus:border-indigo-500 focus:ring-indigo-500/20 rounded-xl"
                viewable
            />
            @error('password')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <flux:checkbox wire:model="remember" id="remember"
                           class="border-white/20 bg-white/5 checked:bg-indigo-500" />
            <label for="remember" class="text-sm text-slate-400 cursor-pointer">Remember me for 30 days</label>
        </div>

        <flux:button
            type="submit"
            variant="primary"
            class="w-full bg-gradient-to-r from-indigo-500 to-violet-600 hover:from-indigo-600 hover:to-violet-700 border-0 text-white font-semibold py-2.5 rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 transition-all"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove wire:target="login">Sign in</span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Signing in…
            </span>
        </flux:button>

    </form>

    {{-- Register link --}}
    <p class="text-center text-sm text-slate-400 mt-6">
        Don't have an account?
        <a href="{{ route('register') }}" wire:navigate
           class="text-indigo-400 hover:text-indigo-300 font-medium">Create one</a>
    </p>

</div>
