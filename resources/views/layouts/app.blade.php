<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $title ?? 'Clinix' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
    @livewireStyles

</head>
<body class="h-full antialiased bg-slate-50 dark:bg-slate-950">

<div class="layout-wrapper">
    
    {{-- Sidebar --}}
    <div class="layout-sidebar">
        <flux:sidebar sticky stashable class="bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 !w-64">

            {{-- Brand --}}
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />
            <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3 px-2 py-4">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 flex items-center justify-center shadow-md">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="font-bold text-slate-900 dark:text-white text-lg">Clinix</span>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group heading="Overview">
                    <flux:navlist.item icon="home" href="{{ route('admin.dashboard') }}" wire:navigate
                                       :active="request()->routeIs('admin.dashboard')">
                        Dashboard
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Healthcare">
                    <flux:navlist.item icon="building-office-2" href="#" wire:navigate>Clinics</flux:navlist.item>
                    <flux:navlist.item icon="beaker" href="#" wire:navigate>Labs</flux:navlist.item>
                    <flux:navlist.item icon="shopping-bag" href="#" wire:navigate>Pharmacy</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="People">
                    <flux:navlist.item icon="users" href="#" wire:navigate>Doctors</flux:navlist.item>
                    <flux:navlist.item icon="user-group" href="#" wire:navigate>Patients</flux:navlist.item>
                    <flux:navlist.item icon="user-plus" href="#" wire:navigate>Staff</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Operations">
                    <flux:navlist.item icon="calendar-days" href="#" wire:navigate>Appointments</flux:navlist.item>
                    <flux:navlist.item icon="document-text" href="#" wire:navigate>Reports</flux:navlist.item>
                    <flux:navlist.item icon="chart-bar" href="#" wire:navigate>Analytics</flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Settings">
                    <flux:navlist.item icon="credit-card" href="#" wire:navigate>Subscription</flux:navlist.item>
                    <flux:navlist.item icon="cog-6-tooth" href="#" wire:navigate>Settings</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            @if(auth()->user()->hasActiveSubscription())
                <div class="mx-2 mb-3 px-3 py-2 rounded-xl bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                        <span class="text-xs font-medium text-indigo-700 dark:text-indigo-300">
                            {{ auth()->user()->activeSubscription->tier->name ?? 'Active Plan' }}
                        </span>
                    </div>
                </div>
            @endif

            <flux:dropdown position="top" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="strtoupper(substr(auth()->user()->name, 0, 2))"
                    icon:trailing="chevron-up-down"
                    class="cursor-pointer"
                />
                <flux:menu>
                    <flux:menu.item icon="user">Profile</flux:menu.item>
                    <flux:menu.item icon="cog-6-tooth">Settings</flux:menu.item>
                    <flux:menu.separator />
                    <flux:menu.item icon="arrow-right-start-on-rectangle"
                                    onclick="document.getElementById('logout-form').submit()">
                        Sign out
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>

        </flux:sidebar>
    </div>

    {{-- Content Area --}}
    <div class="layout-content">
        
        {{-- Header --}}
        <flux:header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 sticky top-0 z-30">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />
            <flux:spacer />
            <div class="flex items-center gap-3">
                <flux:button variant="ghost" icon="bell" size="sm" class="relative">
                    <span class="absolute top-1 right-1 w-2 h-2 rounded-full bg-red-500"></span>
                </flux:button>
                <flux:button variant="ghost" icon="magnifying-glass" size="sm" />
                <flux:dropdown>
                    <flux:avatar
                        :initials="strtoupper(substr(auth()->user()->name, 0, 2))"
                        class="cursor-pointer bg-indigo-100 text-indigo-700 dark:bg-indigo-900 dark:text-indigo-300"
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
            </div>
        </flux:header>

        {{-- Main --}}
        <div class="layout-main bg-slate-50 dark:bg-slate-950">
            {{ $slot }}
        </div>

    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>



@fluxScripts
@livewireScripts
</body>
</html>