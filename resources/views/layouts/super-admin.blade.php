<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Clinix Super Admin' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
{{--
    FIXES:
    1. Removed `overflow-hidden` from <body> — it was clipping the content area and
       preventing the right-side content from scrolling.
    2. Changed body to `flex min-h-screen` instead of `h-full flex overflow-hidden`
       so the layout fills the viewport without cutting anything off.
    3. Removed explicit `h-full` from the sidebar — flux:sidebar handles its own
       sticky/stashable behaviour; forcing h-full caused the gap.
    4. The content wrapper `flex flex-col flex-1 min-w-0 overflow-auto` is kept but
       `min-h-0` is removed — it was collapsing the height inside the flex container
       and leaving an empty space next to the sidebar.
--}}
<body class="min-h-screen antialiased bg-slate-950 flex">

<flux:sidebar sticky stashable
    class="bg-slate-900 border-r border-slate-800 w-64">

    {{-- Brand --}}
    <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
    <div class="flex items-center gap-3 px-2 py-4">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-purple-700 flex items-center justify-center shadow-lg shadow-violet-500/30">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
        <div>
            <div class="font-bold text-white text-sm">Clinix</div>
            <div class="text-xs text-violet-400 font-medium">Super Admin</div>
        </div>
    </div>

    {{-- Super Admin badge --}}
    <div class="mx-3 mb-4 px-3 py-2 rounded-xl bg-violet-500/10 border border-violet-500/20">
        <div class="flex items-center gap-2">
            <div class="w-2 h-2 rounded-full bg-violet-400 animate-pulse"></div>
            <span class="text-xs text-violet-300 font-medium">Platform Control Panel</span>
        </div>
    </div>

    <flux:navlist variant="outline">

        <flux:navlist.group heading="Overview">
            <flux:navlist.item icon="home" href="{{ route('super-admin.dashboard') }}" wire:navigate
                               :active="request()->routeIs('super-admin.dashboard')">
                Dashboard
            </flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group heading="Platform">
            <flux:navlist.item icon="users" href="#" wire:navigate>All Admins</flux:navlist.item>
            <flux:navlist.item icon="building-office-2" href="#" wire:navigate>All Clinics</flux:navlist.item>
            <flux:navlist.item icon="user-group" href="#" wire:navigate>All Patients</flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group heading="Billing">
            <flux:navlist.item icon="credit-card" href="#" wire:navigate>Subscriptions</flux:navlist.item>
            <flux:navlist.item icon="banknotes" href="#" wire:navigate>Revenue</flux:navlist.item>
            <flux:navlist.item icon="tag" href="#" wire:navigate>Plans & Pricing</flux:navlist.item>
        </flux:navlist.group>

        <flux:navlist.group heading="System">
            <flux:navlist.item icon="puzzle-piece" href="#" wire:navigate>Modules</flux:navlist.item>
            <flux:navlist.item icon="bell" href="#" wire:navigate>Notifications</flux:navlist.item>
            <flux:navlist.item icon="shield-check" href="#" wire:navigate>Audit Logs</flux:navlist.item>
            <flux:navlist.item icon="cog-6-tooth" href="#" wire:navigate>System Settings</flux:navlist.item>
        </flux:navlist.group>

    </flux:navlist>

    <flux:spacer />

    <flux:dropdown position="top" align="start">
        <flux:profile
            :name="auth()->user()->name"
            :initials="strtoupper(substr(auth()->user()->name, 0, 2))"
            icon:trailing="chevron-up-down"
            class="cursor-pointer"
        />
        <flux:menu>
            <flux:menu.item icon="user">Profile</flux:menu.item>
            <flux:menu.separator />
            <flux:menu.item icon="arrow-right-start-on-rectangle"
                            onclick="document.getElementById('logout-form').submit()">
                Sign out
            </flux:menu.item>
        </flux:menu>
    </flux:dropdown>

</flux:sidebar>

{{-- Content wrapper — flex-1 fills remaining width; overflow-auto enables page scroll --}}
<div class="flex flex-col flex-1 min-w-0 overflow-auto">

    {{-- Top bar --}}
    <flux:header class="bg-slate-900 border-b border-slate-800 sticky top-0 z-30">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <div class="flex items-center gap-2 ml-2">
            <span class="text-xs px-2.5 py-1 rounded-full bg-violet-500/15 text-violet-400 border border-violet-500/20 font-medium">
                ⚡ Super Admin Mode
            </span>
        </div>

        <flux:spacer />

        <div class="flex items-center gap-3">
            <flux:button variant="ghost" icon="bell" size="sm" />
            <flux:button variant="ghost" icon="magnifying-glass" size="sm" />

            <flux:dropdown>
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center cursor-pointer text-xs font-bold text-white">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <flux:menu>
                    <flux:menu.item icon="user">Profile</flux:menu.item>
                    <flux:menu.separator />
                    <flux:menu.item icon="arrow-right-start-on-rectangle"
                                    onclick="document.getElementById('logout-form').submit()">
                        Sign out
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:header>

    {{-- Main content --}}
    <flux:main class="bg-slate-950 flex-1 p-0">
        {{ $slot }}
    </flux:main>

</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>

@fluxScripts
@livewireScripts
</body>
</html>