<div class="relative" x-data="{ open: false }">
    <!-- Notification Bell -->
    <button @click="open = !open" 
            class="relative p-2 text-slate-400 hover:text-brand-teal transition-colors rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-2 right-2 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white dark:ring-slate-900"></span>
        @endif
    </button>

    <!-- Dropdown -->
    <div x-show="open" 
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute right-0 mt-3 w-80 bg-white dark:bg-slate-900 rounded-[1.5rem] shadow-2xl border border-slate-200 dark:border-slate-800 overflow-hidden z-50">
        
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
            <h3 class="font-bold text-slate-900 dark:text-white">Notifications</h3>
            <span class="text-xs bg-brand-teal/10 text-brand-teal px-2 py-1 rounded-full">{{ $unreadCount }} New</span>
        </div>

        <div class="max-h-96 overflow-y-auto">
            @forelse($notifications as $notification)
                <div class="p-4 hover:bg-slate-50 dark:hover:bg-slate-800/50 border-b border-slate-50 dark:border-slate-800/50 transition-colors cursor-pointer"
                     wire:click="markAsRead('{{ $notification['id'] }}')">
                    <div class="flex gap-3">
                        <div class="w-10 h-10 rounded-full bg-brand-teal/10 flex items-center justify-center text-brand-teal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $notification['data']['title'] ?? 'New Update' }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 mt-1">{{ $notification['data']['message'] ?? '' }}</p>
                            <p class="text-[10px] text-slate-400 mt-2">{{ \Carbon\Carbon::parse($notification['created_at'])->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center">
                    <div class="mx-auto w-12 h-12 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <p class="text-sm text-slate-500">All caught up!</p>
                </div>
            @endforelse
        </div>

        <div class="p-3 bg-slate-50 dark:bg-slate-800/50 text-center">
            <a href="#" class="text-xs font-semibold text-brand-teal hover:underline">View All Notifications</a>
        </div>
    </div>
</div>
