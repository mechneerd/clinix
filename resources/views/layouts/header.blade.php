<header class="h-20 bg-white/80 dark:bg-slate-900/50 backdrop-blur-lg border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-6 sticky top-0 z-40">
    <!-- Left: Mobile Menu & Sidebar Toggle & Search -->
    <div class="flex items-center gap-4">
        <!-- Sidebar Toggles -->
        <div class="flex items-center gap-2">
            <!-- Desktop Collapse Toggle (Visible only when sidebar is expanded) -->
            <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2 text-slate-500 hover:text-brand-teal rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                <svg class="w-6 h-6 transition-transform duration-300" :class="sidebarCollapsed ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>

            <!-- Mobile Menu Toggle -->
            <button @click="sidebarOpen = true" class="lg:hidden p-2 text-slate-500 hover:text-brand-teal rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>
        
        <!-- Global Search -->
        <div class="relative hidden md:block" x-data="{ open: false, query: '' }" @click.away="open = false">
            <input 
                type="text" 
                x-model="query"
                @focus="open = true"
                placeholder="Search everything..."
                class="w-80 pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-500 focus:border-brand-teal focus:ring-2 focus:ring-brand-teal/20 transition-all">
            <svg class="absolute left-3 top-2.5 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <!-- Right: Utils, Notifications & Profile -->
    <div class="flex items-center gap-2 md:gap-4">
        <!-- Dark Mode Toggle -->
        <button 
            @click="document.documentElement.classList.toggle('dark'); localStorage.setItem('theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light')" 
            class="p-2.5 text-slate-500 hover:text-brand-teal rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all"
            title="Toggle Dark Mode"
        >
            <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
            <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 9H3m3.343-5.657l-.707.707m12.728 12.728l-.707.707M6.343 17.657l-.707-.707M17.657 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
        </button>

        @auth
            <!-- Notifications -->
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="relative p-2.5 text-slate-500 hover:text-brand-teal rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    @php
                    $unreadCount = auth()->user()->notifications()->whereNull('read_at')->count();
                    @endphp
                    @if($unreadCount > 0)
                    <span class="absolute top-2 right-2 w-2 h-2 bg-brand-green rounded-full shadow-sm ring-2 ring-white dark:ring-slate-900"></span>
                    @endif
                </button>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }" @click.away="open = false">
                <button @click="open = !open" class="flex items-center gap-3 p-1 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-all">
                    <div class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-brand-teal font-bold text-sm border-2 border-white dark:border-slate-800 shadow-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="hidden lg:block text-left mr-1">
                        <p class="text-sm font-semibold text-slate-900 dark:text-white leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] uppercase tracking-tighter text-slate-500 font-bold leading-tight">{{ auth()->user()->getRoleNames()->first() }}</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 hidden lg:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="absolute right-0 mt-2 w-56 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-xl overflow-hidden z-50">
                    <div class="p-4 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                        <p class="font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="p-2 text-sm text-slate-600 dark:text-slate-300">
                        <a href="{{ route('profile') }}" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-700 hover:text-brand-teal transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            My Profile
                        </a>
                        <div class="border-t border-slate-100 dark:border-slate-700 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="px-5 py-2.5 bg-brand-teal text-white rounded-xl font-bold hover:shadow-lg hover:shadow-brand-teal/25 transition-all text-sm">
                Login
            </a>
        @endauth
    </div>
</header>