<div class="p-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">App Modules</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Manage global availability of clinical features.</p>
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($modules as $module)
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden group">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-brand-teal group-hover:bg-brand-teal group-hover:text-white transition-colors duration-300">
                            <x-icons :name="$module->icon ?? 'box'" class="w-6 h-6" />
                        </div>
                        <div class="flex items-center gap-2">
                             @if($module->is_core)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-700 text-slate-500">Core</span>
                            @endif
                            <button 
                                wire:click="toggleModule({{ $module->id }})"
                                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-brand-teal focus:ring-offset-2 {{ $module->is_active ? 'bg-brand-teal' : 'bg-slate-200 dark:bg-slate-600' }}"
                            >
                                <span class="sr-only">Toggle module</span>
                                <span 
                                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $module->is_active ? 'translate-x-5' : 'translate-x-0' }}"
                                ></span>
                            </button>
                        </div>
                    </div>
                    
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ $module->name }}</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-4">
                        {{ $module->description ?? 'No description provided for this module.' }}
                    </p>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-slate-700">
                        <span class="text-xs font-semibold uppercase tracking-wider {{ $module->is_active ? 'text-emerald-500' : 'text-slate-400' }}">
                            {{ $module->is_active ? 'Active' : 'Disabled' }}
                        </span>
                        <span class="text-[10px] text-slate-400 font-medium">Slug: {{ $module->slug }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
