<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('super-admin.clinics') }}" class="text-slate-400 hover:text-brand-teal transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7 7-7"></path></svg>
                </a>
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Manage Modules: {{ $user->name }}</h1>
            </div>
            <p class="text-slate-500 dark:text-slate-400">Settings here apply to <strong>all clinics</strong> managed by this admin.</p>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
    @endif

     @if (session()->has('error'))
        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-500 rounded-xl flex items-center gap-3 animate-in fade-in slide-in-from-top-4 duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50">
                    <th class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Module</th>
                    <th class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider text-center">Global Status</th>
                    <th class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider text-center">Admin Access</th>
                    <th class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider text-right">Effective Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @foreach($modules as $module)
                    @php
                        $isEnabled = $user->isModuleEnabled($module->slug);
                        $hasOverride = $user->modules()->where('module_id', $module->id)->exists();
                    @endphp
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-brand-teal">
                                    <x-icons :name="$module->icon ?? 'box'" class="w-5 h-5" />
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white">{{ $module->name }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">{{ $module->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($module->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200">Enabled</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 dark:bg-rose-900 text-rose-800 dark:text-rose-200">Disabled</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($module->is_core)
                                <span class="text-xs font-semibold text-slate-400">Core (Unlocked)</span>
                            @else
                                <button 
                                    wire:click="toggleModule({{ $module->id }})"
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 {{ $isEnabled ? 'bg-brand-teal' : 'bg-slate-200 dark:bg-slate-600' }}"
                                >
                                    <span class="sr-only">Toggle admin module</span>
                                    <span 
                                        class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $isEnabled ? 'translate-x-5' : 'translate-x-0' }}"
                                    ></span>
                                </button>
                                @if($hasOverride)
                                    <div class="text-[10px] mt-1 font-bold text-brand-teal uppercase tracking-tighter">Admin Override</div>
                                @endif
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            @if($isEnabled)
                                <div class="flex items-center justify-end gap-2 text-emerald-500 font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    Active
                                </div>
                            @else
                                <div class="flex items-center justify-end gap-2 text-slate-400 font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    Disabled
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
