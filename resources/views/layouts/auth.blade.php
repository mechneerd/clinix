<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Clinix' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="h-full antialiased bg-gradient-to-br from-slate-950 via-indigo-950 to-slate-900">

    {{-- Decorative blobs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] rounded-full bg-indigo-600/20 blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full bg-violet-600/20 blur-[120px]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] rounded-full bg-indigo-900/10 blur-[80px]"></div>
    </div>

    {{-- Grid pattern --}}
    <div class="fixed inset-0 bg-[url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 32 32%22 width=%2232%22 height=%2232%22 fill=%22none%22 stroke=%22rgb(255 255 255 / 0.03)%22%3E%3Cpath d=%22M0 .5H31.5V32%22/%3E%3C/svg%3E')] pointer-events-none"></div>

    <div class="relative min-h-screen flex flex-col items-center justify-center p-4">

        {{-- Logo / Brand --}}
        <div class="mb-8 text-center">
            <a href="/" wire:navigate class="inline-flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-xl shadow-indigo-500/30 group-hover:shadow-indigo-500/50 transition-shadow">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-white tracking-tight">Clinix</span>
            </a>
            <p class="mt-2 text-sm text-slate-400">Smart Healthcare Management Platform</p>
        </div>

        {{-- Card --}}
        <div class="w-full max-w-md">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl shadow-2xl shadow-black/40 overflow-hidden">
                {{ $slot }}
            </div>
        </div>

        {{-- Footer --}}
        <p class="mt-8 text-xs text-slate-500">
            © {{ date('Y') }} Clinix. All rights reserved.
            <a href="#" class="text-indigo-400 hover:text-indigo-300 ml-2">Privacy</a>
            <a href="#" class="text-indigo-400 hover:text-indigo-300 ml-2">Terms</a>
        </p>
    </div>

    @fluxScripts
    @livewireScripts
</body>
</html>
