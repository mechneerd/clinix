<div class="p-6 lg:p-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.labs.index', $clinic->id) }}" wire:navigate class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                <flux:icon name="arrow-left" class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Lab Tests — {{ $lab->name }}</h1>
                <p class="text-slate-500 text-sm">{{ $clinic->name }}</p>
            </div>
        </div>
        <flux:button wire:click="openCreate" class="bg-violet-600 text-white border-0 rounded-xl" icon="plus">Add Test</flux:button>
    </div>

    {{-- Category filter --}}
    <div class="flex flex-wrap gap-2">
        <button wire:click="$set('categoryFilter', null)"
                @class(['px-4 py-1.5 rounded-full text-sm font-medium transition-all',
                        'bg-violet-600 text-white shadow-sm' => !$categoryFilter,
                        'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300' => $categoryFilter])>All</button>
        @foreach ($categories as $cat)
            <button wire:click="$set('categoryFilter', {{ $cat->id }})"
                    @class(['px-4 py-1.5 rounded-full text-sm font-medium transition-all',
                            'bg-violet-600 text-white shadow-sm' => $categoryFilter === $cat->id,
                            'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300' => $categoryFilter !== $cat->id])>{{ $cat->name }}</button>
        @endforeach
    </div>

    {{-- Tests Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="border-b border-slate-100 dark:border-slate-800">
                <tr>
                    @foreach (['Test Name','Code','Category','Price','Turnaround','Result Type','Status','Actions'] as $h)
                        <th class="text-left px-5 py-3 text-xs font-medium text-slate-500 uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($tests as $test)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="font-medium text-slate-900 dark:text-white text-sm">{{ $test->name }}</div>
                            @if ($test->sample_type)
                                <div class="text-xs text-slate-500">Sample: {{ $test->sample_type }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-xs font-mono text-slate-600 dark:text-slate-400">{{ $test->code ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $test->category->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-sm font-semibold text-slate-900 dark:text-white">₹{{ number_format($test->price, 2) }}</td>
                        <td class="px-5 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $test->default_turnaround_time }}h</td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2 py-0.5 rounded-full bg-violet-100 dark:bg-violet-900/30 text-violet-700 dark:text-violet-300 capitalize">{{ $test->result_type }}</span>
                        </td>
                        <td class="px-5 py-3">
                            <span @class(['text-xs px-2.5 py-0.5 rounded-full font-medium',
                                          'bg-green-100 text-green-700' => $test->is_active,
                                          'bg-slate-100 text-slate-500' => !$test->is_active])>
                                {{ $test->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <button wire:click="openEdit({{ $test->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 transition-colors">
                                <flux:icon name="pencil" class="w-4 h-4" />
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center text-slate-500 text-sm">
                            No tests found. <button wire:click="openCreate" class="text-violet-600 underline">Add your first test</button>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-800">{{ $tests->links() }}</div>
    </div>

    {{-- Slide-over form --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex justify-end bg-black/40 backdrop-blur-sm" wire:click="$set('showForm',false)">
            <div class="w-full max-w-lg bg-white dark:bg-slate-900 h-full overflow-y-auto shadow-2xl" wire:click.stop>
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="font-bold text-slate-900 dark:text-white">{{ $editingId ? 'Edit' : 'New' }} Test</h2>
                    <button wire:click="$set('showForm',false)" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <flux:icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Test Name *</flux:label>
                            <flux:input wire:model="name" placeholder="Complete Blood Count" class="mt-1 w-full rounded-xl" />
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Code</flux:label>
                            <flux:input wire:model="code" placeholder="CBC" class="mt-1 w-full rounded-xl font-mono" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Category</flux:label>
                            <flux:select wire:model="category_id" class="mt-1 w-full rounded-xl">
                                <option value="">None</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </flux:select>
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Price (₹) *</flux:label>
                            <flux:input wire:model="price" type="number" min="0" step="0.01" class="mt-1 w-full rounded-xl" />
                            @error('price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Turnaround (hrs)</flux:label>
                            <flux:input wire:model="default_turnaround_time" type="number" min="1" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Result Type *</flux:label>
                            <flux:select wire:model="result_type" class="mt-1 w-full rounded-xl">
                                <option value="text">Text</option>
                                <option value="numeric">Numeric</option>
                                <option value="boolean">Boolean (Yes/No)</option>
                                <option value="multiple_choice">Multiple Choice</option>
                                <option value="file">File Upload</option>
                            </flux:select>
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Unit</flux:label>
                            <flux:input wire:model="unit" placeholder="mg/dL, g/L…" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Sample Type</flux:label>
                            <flux:input wire:model="sample_type" placeholder="Blood, Urine…" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div class="col-span-2">
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Normal Values</flux:label>
                            <flux:textarea wire:model="normal_values" rows="2" placeholder="e.g. 70-110 mg/dL" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div class="col-span-2">
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Preparation Instructions</flux:label>
                            <flux:textarea wire:model="preparation_instructions" rows="2" placeholder="e.g. Fast for 8 hours before test" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div class="col-span-2 flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                            <input type="checkbox" wire:model="is_active" class="w-4 h-4 rounded text-violet-600" id="test-active" />
                            <label for="test-active" class="text-sm text-slate-700 dark:text-slate-300">Active</label>
                        </div>
                    </div>
                    <flux:button wire:click="save" class="w-full bg-violet-600 text-white border-0 rounded-xl">
                        {{ $editingId ? 'Update' : 'Create' }} Test
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

</div>
