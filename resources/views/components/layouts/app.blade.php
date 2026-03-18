<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title x-text="$wire.pageTitle || 'Clinix'">Clinix</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        [x-cloak] { display: none !important; }
        .fade-enter { opacity: 0; transform: translateY(10px); }
        .fade-enter-active { opacity: 1; transform: translateY(0); transition: all 0.3s ease; }
        .slide-enter { transform: translateX(-100%); }
        .slide-enter-active { transform: translateX(0); transition: transform 0.3s ease; }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #1e293b; }
        ::-webkit-scrollbar-thumb { background: #475569; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        
        /* Loading overlay */
        .page-loading {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(4px);
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-hidden" x-data="{ sidebarOpen: false, sidebarCollapsed: $persist(false) }">
    <script>
        // Theme initialization
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    
    <!-- Toast Notifications -->
    <div class="fixed top-4 right-4 z-50 space-y-2" x-data="{ toasts: [] }" @toast.window="addToast($event.detail)">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full" :class="{
                'bg-emerald-500/20 border-emerald-500/50 text-emerald-400': toast.type === 'success',
                'bg-rose-500/20 border-rose-500/50 text-rose-400': toast.type === 'error',
                'bg-amber-500/20 border-amber-500/50 text-amber-400': toast.type === 'warning',
                'bg-cyan-500/20 border-cyan-500/50 text-cyan-400': toast.type === 'info'
            }" class="px-4 py-3 rounded-xl border backdrop-blur-sm shadow-lg min-w-[300px] flex items-start gap-3">
                <svg x-show="toast.type === 'success'" class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <svg x-show="toast.type === 'error'" class="w-5 h-5 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="flex-1">
                    <p x-text="toast.message" class="text-sm font-medium"></p>
                </div>
                <button @click="toast.show = false" class="text-current opacity-70 hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </template>
    </div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        @include('layouts.sidebar')
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-slate-50 dark:bg-slate-950">
            <!-- Top Header -->
            @include('layouts.header')
            
            <!-- Main Scrollable Content -->
            <main class="flex-1 overflow-y-auto p-6 relative custom-scrollbar" id="main-content">
                <!-- Transition Wrapper -->
                <div x-show="true" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
