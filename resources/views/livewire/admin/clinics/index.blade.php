<div class="p-6 lg:p-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">My Clinics</h1>
            <p class="text-slate-500 text-sm mt-1">Manage all your healthcare facilities</p>
        </div>
        @if ($canCreate)
            <a href="{{ route('admin.clinics.create') }}" wire:navigate>
                <flux:button class="bg-indigo-600 hover:bg-indigo-700 text-white border-0 rounded-xl" icon="plus">
                    Create Clinic
                </flux:button>
            </a>
        @else
            <div class="text-xs text-amber-600 bg-amber-50 border border-amber-200 px-4 py-2 rounded-xl">
                ⚠️ Clinic limit reached. <a href="{{ route('subscription.select') }}" class="underline font-medium">Upgrade plan</a>
            </div>
        @endif
    </div>

    {{-- Clinics Grid --}}
    @if ($clinics->isEmpty())
        <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 p-20 text-center">
            <div class="w-20 h-20 mx-auto mb-5 rounded-3xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center">
                <flux:icon name="building-office-2" class="w-10 h-10 text-indigo-400" />
            </div>
            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No clinics yet</h3>
            <p class="text-slate-500 mb-6">Create your first clinic to start managing patients, staff, and appointments.</p>
            <a href="{{ route('admin.clinics.create') }}" wire:navigate>
                <flux:button class="bg-indigo-600 text-white border-0 rounded-xl px-8">Create Your First Clinic</flux:button>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @foreach ($clinics as $clinic)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-lg transition-all overflow-hidden group">

                    {{-- Banner / Header --}}
                    <div class="h-2 w-full" style="background: linear-gradient(135deg, {{ $clinic->primary_color }}, {{ $clinic->secondary_color }})"></div>

                    <div class="p-5">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-3">
                                {{-- Logo --}}
                                @if ($clinic->logo_url)
                                    <img src="{{ $clinic->logo_url }}" class="w-12 h-12 rounded-xl object-cover" />
                                @else
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white text-lg font-bold"
                                         style="background: linear-gradient(135deg, {{ $clinic->primary_color }}, {{ $clinic->secondary_color }})">
                                        {{ strtoupper(substr($clinic->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white text-sm leading-tight">{{ $clinic->name }}</h3>
                                    <p class="text-xs text-slate-500">{{ $clinic->city }}, {{ $clinic->state }}</p>
                                </div>
                            </div>

                            {{-- Status toggle --}}
                            <div class="flex items-center gap-2">
                                <button wire:click="toggleStatus({{ $clinic->id }})"
                                        @class(['w-10 h-5 rounded-full transition-colors relative flex-shrink-0',
                                                'bg-green-500' => $clinic->is_active,
                                                'bg-slate-300 dark:bg-slate-700' => !$clinic->is_active])>
                                    <span @class(['absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform',
                                                  'translate-x-5' => $clinic->is_active,
                                                  'translate-x-0.5' => !$clinic->is_active])></span>
                                </button>
                            </div>
                        </div>

                        {{-- Stats row --}}
                        <div class="grid grid-cols-4 gap-2 mb-4">
                            @foreach ([
                                ['label'=>'Staff',    'count'=>$clinic->staff_count,        'color'=>'indigo'],
                                ['label'=>'Depts',    'count'=>$clinic->departments_count,   'color'=>'blue'],
                                ['label'=>'Labs',     'count'=>$clinic->labs_count,          'color'=>'violet'],
                                ['label'=>'Pharmacy', 'count'=>$clinic->pharmacies_count,    'color'=>'emerald'],
                            ] as $s)
                                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-2 text-center">
                                    <div class="text-lg font-bold text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400">{{ $s['count'] }}</div>
                                    <div class="text-xs text-slate-500">{{ $s['label'] }}</div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Actions --}}
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('admin.clinics.show', $clinic->id) }}" wire:navigate class="flex-1">
                                <flux:button size="xs" class="w-full bg-indigo-600 text-white border-0 rounded-lg" icon="eye">
                                    Manage
                                </flux:button>
                            </a>
                            <a href="{{ route('admin.staff.index', $clinic->id) }}" wire:navigate>
                                <flux:button size="xs" variant="ghost" class="border-slate-200 dark:border-slate-700 rounded-lg" icon="users">
                                    Staff
                                </flux:button>
                            </a>
                            <a href="{{ route('admin.labs.index', $clinic->id) }}" wire:navigate>
                                <flux:button size="xs" variant="ghost" class="border-slate-200 dark:border-slate-700 rounded-lg" icon="beaker">
                                    Labs
                                </flux:button>
                            </a>
                            <a href="{{ route('admin.pharmacies.index', $clinic->id) }}" wire:navigate>
                                <flux:button size="xs" variant="ghost" class="border-slate-200 dark:border-slate-700 rounded-lg" icon="shopping-bag">
                                    Rx
                                </flux:button>
                            </a>
                            <button wire:click="confirmDelete({{ $clinic->id }})"
                                    class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-400 hover:text-red-600 transition-colors">
                                <flux:icon name="trash" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Delete modal --}}
    @if ($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-md p-6">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-red-50 flex items-center justify-center">
                    <flux:icon name="trash" class="w-7 h-7 text-red-500" />
                </div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white text-center mb-2">Delete Clinic?</h3>
                <p class="text-slate-500 text-sm text-center mb-6">This will permanently delete the clinic and all its data. This cannot be undone.</p>
                <div class="flex gap-3">
                    <flux:button wire:click="$set('showDeleteModal',false)" variant="ghost" class="flex-1">Cancel</flux:button>
                    <flux:button wire:click="delete" class="flex-1 bg-red-600 text-white border-0">Yes, Delete</flux:button>
                </div>
            </div>
        </div>
    @endif

</div>
