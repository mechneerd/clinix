<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Clinix Patient' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles
</head>
{{--
    FIXES:
    1. Added `flex` to <body> — without it, flux:sidebar and flux:main sit in a block
       context, causing the main content to render below (or behind) the sidebar
       instead of beside it. This was why the content area appeared blank.
    2. Replaced `flux:main` with a plain `<div class="flex flex-col flex-1 min-w-0
       overflow-auto">` content wrapper (same pattern as the working super-admin
       layout). flux:main doesn't receive flex-1 by default in all Flux versions,
       so the explicit wrapper is more reliable.
    3. Added `@livewireStyles` and `@livewireScripts` — were missing from the
       original patient layout, which could cause Livewire components to not hydrate.
    4. Moved flux:header inside the content wrapper div so it scrolls with the page
       and doesn't overlap the sidebar on mobile.
--}}
<body class="h-full antialiased bg-slate-50 dark:bg-slate-950 flex">

<flux:sidebar sticky stashable class="bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 w-64">
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

    {{-- Brand --}}
    <a href="{{ route('patient.dashboard') }}" wire:navigate class="flex items-center gap-3 px-2 py-4">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-md">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </div>
        <div>
            <div class="font-bold text-slate-900 dark:text-white text-sm">Clinix</div>
            <div class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Patient Portal</div>
        </div>
    </a>

    <flux:navlist variant="outline">

        <flux:navlist.group heading="Overview">
            <flux:navlist.item icon="home" href="{{ route('patient.dashboard') }}" wire:navigate
                               :active="request()->routeIs('patient.dashboard')">
                Dashboard
            </flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group heading="Healthcare">
            <flux:navlist.item icon="calendar-days" href="{{ route('patient.appointments') }}" wire:navigate
                               :active="request()->routeIs('patient.appointments')">
                My Appointments
            </flux:navlist.item>
            <flux:navlist.item icon="plus-circle" href="{{ route('patient.book-appointment') }}" wire:navigate
                               :active="request()->routeIs('patient.book-appointment')">
                Book Appointment
            </flux:navlist.item>
            <flux:navlist.item icon="beaker" href="{{ route('patient.lab-orders') }}" wire:navigate
                               :active="request()->routeIs('patient.lab-orders')">
                Lab Results
            </flux:navlist.item>
            <flux:navlist.item icon="document-text" href="{{ route('patient.prescriptions') }}" wire:navigate
                               :active="request()->routeIs('patient.prescriptions')">
                Prescriptions
            </flux:navlist.item>
            <flux:navlist.item icon="clipboard-document-list" href="{{ route('patient.reports') }}" wire:navigate
                               :active="request()->routeIs('patient.reports')">
                Health Reports
            </flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group heading="Account">
            <flux:navlist.item icon="user" href="#" wire:navigate>My Profile</flux:navlist.item>
            <flux:navlist.item icon="bell" href="#" wire:navigate>Notifications</flux:navlist.item>
        </flux:navlist.group>

    </flux:navlist>

    <flux:spacer />

    {{-- Patient info --}}
    <div class="mx-2 mb-3 px-3 py-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-100 dark:border-emerald-900">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
            <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Patient Account</span>
        </div>
        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
    </div>

    <flux:dropdown position="top" align="start">
        <flux:profile
            :name="auth()->user()->name"
            :initials="strtoupper(substr(auth()->user()->name, 0, 2))"
            icon:trailing="chevron-up-down"
            class="cursor-pointer"
        />
        <flux:menu>
            <flux:menu.item icon="user">My Profile</flux:menu.item>
            <flux:menu.separator />
            <flux:menu.item icon="arrow-right-start-on-rectangle"
                            onclick="document.getElementById('logout-form').submit()">
                Sign out
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>

</flux:sidebar>

{{-- Content wrapper — sits beside the sidebar, fills remaining width --}}
<div class="flex flex-col flex-1 min-w-0 overflow-auto">

    {{-- Header --}}
    <flux:header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        {{-- Notification bell --}}
        <flux:dropdown align="end">
            <flux:button variant="ghost" icon="bell" size="sm" class="relative">
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="absolute top-1 right-1 w-4 h-4 rounded-full bg-red-500 text-white text-[10px] flex items-center justify-center font-bold">
                        {{ auth()->user()->unreadNotifications->count() }}
                    </span>
                @endif
            </flux:button>
            <flux:menu class="w-80">
                <div class="flex items-center justify-between px-3 py-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="text-sm font-semibold text-slate-900 dark:text-white">Notifications</span>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <button wire:click="markAllRead" class="text-xs text-emerald-600 hover:underline">Mark all read</button>
                    @endif
                </div>
                @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                    <flux:menu.item class="flex flex-col items-start gap-0.5 py-3">
                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $notification->data['title'] ?? '' }}</span>
                        <span class="text-xs text-slate-500">{{ $notification->data['body'] ?? '' }}</span>
                        <span class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</span>
                    </flux:menu.item>
                @empty
                    <div class="px-4 py-6 text-center text-sm text-slate-500">No new notifications</div>
                @endforelse
            </flux:menu>
        </flux:dropdown>

        <flux:button variant="primary" size="sm" href="{{ route('patient.book-appointment') }}" wire:navigate
                     class="bg-emerald-600 hover:bg-emerald-700 border-0 text-white rounded-xl ml-2">
            Book Appointment
        </flux:button>
    </flux:header>

    {{-- Toast notification container (for Reverb real-time toasts) --}}
    <div
        x-data="{ toasts: [] }"
        @notify.window="toasts.push($event.detail[0]); setTimeout(() => toasts.shift(), 4000)"
        class="fixed top-4 right-4 z-50 space-y-2 w-80"
    >
        <template x-for="(toast, i) in toasts" :key="i">
            <div x-transition class="flex items-start gap-3 p-4 rounded-2xl shadow-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                     :class="toast.type === 'success' ? 'bg-emerald-100 text-emerald-600' : 'bg-blue-100 text-blue-600'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-900 dark:text-white" x-text="toast.title"></p>
                    <p class="text-xs text-slate-500 mt-0.5" x-text="toast.message"></p>
                </div>
            </div>
        </template>
    </div>

    {{-- Main content --}}
    <main class="bg-slate-50 dark:bg-slate-950 flex-1">
        {{ $slot }}
    </main>

</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

{{-- Reverb / Echo setup --}}
<script>
    window.authUserId = {{ auth()->id() }};
</script>

@fluxScripts
@livewireScripts
</body>
</html>