<div class="p-6 lg:p-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.clinics.show', $clinic->id) }}" wire:navigate class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                <flux:icon name="arrow-left" class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Laboratories</h1>
                <p class="text-slate-500 text-sm">{{ $clinic->name }}</p>
            </div>
        </div>
        <flux:button wire:click="openCreate" class="bg-violet-600 text-white border-0 rounded-xl" icon="plus">New Lab</flux:button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($labs as $lab)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <div class="h-1.5 bg-gradient-to-r from-violet-500 to-purple-600"></div>
                <div class="p-5">
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-12 h-12 rounded-xl bg-violet-50 dark:bg-violet-900/20 flex items-center justify-center">
                            <flux:icon name="beaker" class="w-6 h-6 text-violet-600 dark:text-violet-400" />
                        </div>
                        <span @class(['text-xs px-2.5 py-0.5 rounded-full font-medium',
                                      'bg-green-100 text-green-700' => $lab->is_active,
                                      'bg-slate-100 text-slate-500' => !$lab->is_active])>
                            {{ $lab->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <h3 class="font-bold text-slate-900 dark:text-white mb-1">{{ $lab->name }}</h3>
                    @if ($lab->description)
                        <p class="text-sm text-slate-500 mb-3 line-clamp-2">{{ $lab->description }}</p>
                    @endif
                    <div class="grid grid-cols-2 gap-2 mb-4">
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-2.5 text-center">
                            <div class="text-xl font-bold text-violet-600">{{ $lab->tests_count }}</div>
                            <div class="text-xs text-slate-500">Tests</div>
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-2.5 text-center">
                            <div class="text-xl font-bold text-violet-600">{{ $lab->orders_count }}</div>
                            <div class="text-xs text-slate-500">Orders</div>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.labs.tests', [$clinic->id, $lab->id]) }}" wire:navigate class="flex-1">
                            <flux:button size="xs" class="w-full bg-violet-600 text-white border-0 rounded-lg" icon="beaker">Manage Tests</flux:button>
                        </a>
                        <button wire:click="openEdit({{ $lab->id }})" class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800">
                            <flux:icon name="pencil" class="w-4 h-4 text-slate-500" />
                        </button>
                        <button wire:click="deleteLab({{ $lab->id }})" class="p-1.5 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-red-50 text-slate-400 hover:text-red-500">
                            <flux:icon name="trash" class="w-4 h-4" />
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-16 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-violet-50 dark:bg-violet-900/20 flex items-center justify-center">
                    <flux:icon name="beaker" class="w-8 h-8 text-violet-400" />
                </div>
                <h3 class="font-semibold text-slate-900 dark:text-white mb-2">No labs yet</h3>
                <p class="text-slate-500 text-sm mb-4">Add a laboratory to manage tests and reports.</p>
                <flux:button wire:click="openCreate" class="bg-violet-600 text-white border-0 rounded-xl">Create First Lab</flux:button>
            </div>
        @endforelse
    </div>

    {{-- Slide-over form --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex justify-end bg-black/40 backdrop-blur-sm" wire:click="$set('showForm',false)">
            <div class="w-full max-w-md bg-white dark:bg-slate-900 h-full overflow-y-auto shadow-2xl" wire:click.stop>
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="font-bold text-slate-900 dark:text-white">{{ $editingId ? 'Edit' : 'New' }} Lab</h2>
                    <button wire:click="$set('showForm',false)" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <flux:icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Lab Name *</flux:label>
                        <flux:input wire:model="name" placeholder="e.g. Pathology Lab" class="mt-1 w-full rounded-xl" />
                        @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Description</flux:label>
                        <flux:textarea wire:model="description" rows="3" class="mt-1 w-full rounded-xl" />
                    </div>
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Email</flux:label>
                        <flux:input wire:model="email" type="email" class="mt-1 w-full rounded-xl" />
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Phone</flux:label>
                        <flux:input wire:model="phone" type="tel" class="mt-1 w-full rounded-xl" />
                    </div>
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Address</flux:label>
                        <flux:textarea wire:model="address" rows="2" class="mt-1 w-full rounded-xl" />
                    </div>
                    <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                        <input type="checkbox" wire:model="is_active" class="w-4 h-4 rounded text-violet-600" id="lab-active" />
                        <label for="lab-active" class="text-sm text-slate-700 dark:text-slate-300">Active</label>
                    </div>
                    <flux:button wire:click="save" class="w-full bg-violet-600 text-white border-0 rounded-xl">
                        {{ $editingId ? 'Update' : 'Create' }} Lab
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

</div>
