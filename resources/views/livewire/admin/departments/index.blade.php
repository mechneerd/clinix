<div class="p-6 lg:p-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.clinics.show', $clinic->id) }}" wire:navigate class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                <flux:icon name="arrow-left" class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Departments</h1>
                <p class="text-slate-500 text-sm">{{ $clinic->name }}</p>
            </div>
        </div>
        <flux:button wire:click="openCreate" class="bg-indigo-600 text-white border-0 rounded-xl" icon="plus">
            Add Department
        </flux:button>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse ($departments as $dept)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 flex items-center justify-center">
                        <flux:icon :name="$dept->icon ?? 'building-office-2'" class="w-6 h-6 text-indigo-500" />
                    </div>
                    <div class="flex items-center gap-1">
                        <button wire:click="toggleStatus({{ $dept->id }})"
                                @class(['w-9 h-5 rounded-full transition-colors relative',
                                        'bg-green-500' => $dept->is_active,
                                        'bg-slate-200 dark:bg-slate-700' => !$dept->is_active])>
                            <span @class(['absolute top-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform',
                                          'translate-x-4' => $dept->is_active,
                                          'translate-x-0.5' => !$dept->is_active])></span>
                        </button>
                    </div>
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-0.5">{{ $dept->name }}</h3>
                @if ($dept->code)
                    <p class="text-xs text-slate-400 font-mono mb-1">{{ $dept->code }}</p>
                @endif
                @if ($dept->description)
                    <p class="text-sm text-slate-500 mb-3 line-clamp-2">{{ $dept->description }}</p>
                @endif
                <div class="flex items-center justify-between mt-3 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <span class="text-xs text-slate-500">{{ $dept->appointments_count }} appointments</span>
                    <div class="flex gap-1">
                        <button wire:click="openEdit({{ $dept->id }})"
                                class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 transition-colors">
                            <flux:icon name="pencil" class="w-4 h-4" />
                        </button>
                        <button wire:click="delete({{ $dept->id }})"
                                class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-slate-400 hover:text-red-500 transition-colors">
                            <flux:icon name="trash" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-16 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                    <flux:icon name="building-office-2" class="w-7 h-7 text-slate-400" />
                </div>
                <h3 class="font-semibold text-slate-900 dark:text-white mb-2">No departments yet</h3>
                <p class="text-slate-500 text-sm mb-4">Create departments like Cardiology, General Practice, etc.</p>
                <flux:button wire:click="openCreate" class="bg-indigo-600 text-white border-0 rounded-xl">Add First Department</flux:button>
            </div>
        @endforelse
    </div>

    {{-- Slide-over form --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex justify-end bg-black/40 backdrop-blur-sm" wire:click="$set('showForm',false)">
            <div class="w-full max-w-md bg-white dark:bg-slate-900 h-full overflow-y-auto shadow-2xl" wire:click.stop>
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="font-bold text-slate-900 dark:text-white">{{ $editingId ? 'Edit' : 'New' }} Department</h2>
                    <button wire:click="$set('showForm',false)" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <flux:icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Name *</flux:label>
                        <flux:input wire:model="name" placeholder="e.g. Cardiology" class="mt-1 w-full rounded-xl" />
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Code</flux:label>
                        <flux:input wire:model="code" placeholder="e.g. CARD" class="mt-1 w-full rounded-xl font-mono" />
                    </div>
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Icon</flux:label>
                        <flux:select wire:model="icon" class="mt-1 w-full rounded-xl">
                            @foreach (['building-office-2','heart','beaker','eye','user','bolt','sun','moon','shield-check','academic-cap'] as $ico)
                                <option value="{{ $ico }}">{{ $ico }}</option>
                            @endforeach
                        </flux:select>
                    </div>
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Description</flux:label>
                        <flux:textarea wire:model="description" rows="3" class="mt-1 w-full rounded-xl" />
                    </div>
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Sort Order</flux:label>
                        <flux:input wire:model="sort_order" type="number" min="0" class="mt-1 w-full rounded-xl" />
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                        <input type="checkbox" wire:model="is_active" class="w-4 h-4 rounded text-indigo-600" id="dept-active" />
                        <label for="dept-active" class="text-sm text-slate-700 dark:text-slate-300">Active</label>
                    </div>
                    <flux:button wire:click="save" class="w-full bg-indigo-600 text-white border-0 rounded-xl">
                        {{ $editingId ? 'Update' : 'Create' }} Department
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

</div>
