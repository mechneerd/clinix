<div class="p-6 lg:p-8 space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pharmacies.index', $clinic->id) }}" wire:navigate class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                <flux:icon name="arrow-left" class="w-4 h-4" />
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Medicines — {{ $pharmacy->name }}</h1>
                <p class="text-slate-500 text-sm">{{ $clinic->name }}</p>
            </div>
        </div>
        <flux:button wire:click="openCreate" class="bg-emerald-600 text-white border-0 rounded-xl" icon="plus">Add Medicine</flux:button>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ([
            ['label'=>'Total Medicines', 'value'=>$stats['total_medicines'], 'color'=>'emerald'],
            ['label'=>'Low Stock',       'value'=>$stats['low_stock'],       'color'=>'amber'],
            ['label'=>'Out of Stock',    'value'=>$stats['out_of_stock'],    'color'=>'red'],
            ['label'=>'Categories',      'value'=>$stats['categories'],      'color'=>'blue'],
        ] as $s)
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5 shadow-sm">
                <div class="text-2xl font-bold text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400">{{ $s['value'] }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $s['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-48">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search medicine…" icon="magnifying-glass" class="w-full rounded-xl" />
        </div>
        <flux:select wire:model.live="categoryFilter" class="rounded-xl w-44">
            <option value="">All Categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </flux:select>
    </div>

    {{-- Medicines Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-slate-100 dark:border-slate-800">
                    <tr>
                        @foreach (['Medicine','Type','Category','Stock','MRP','Selling Price','Expiry','Status','Actions'] as $h)
                            <th class="text-left px-4 py-3 text-xs font-medium text-slate-500 uppercase whitespace-nowrap">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse ($medicines as $med)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                            <td class="px-4 py-3">
                                <div class="font-medium text-slate-900 dark:text-white text-sm">{{ $med->name }}</div>
                                @if ($med->generic_name)
                                    <div class="text-xs text-slate-500">{{ $med->generic_name }}</div>
                                @endif
                                @if ($med->strength)
                                    <div class="text-xs text-slate-400 font-mono">{{ $med->strength }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 capitalize">{{ $med->type }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $med->category->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span @class(['text-sm font-semibold',
                                              'text-red-600' => $med->current_stock === 0,
                                              'text-amber-600' => $med->current_stock > 0 && $med->isLowStock(),
                                              'text-slate-900 dark:text-white' => !$med->isLowStock()])>
                                    {{ $med->current_stock }}
                                </span>
                                @if ($med->current_stock === 0)
                                    <span class="ml-1 text-xs text-red-500">(Out)</span>
                                @elseif ($med->isLowStock())
                                    <span class="ml-1 text-xs text-amber-500">(Low)</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-400">₹{{ number_format($med->mrp, 2) }}</td>
                            <td class="px-4 py-3 text-sm font-semibold text-slate-900 dark:text-white">₹{{ number_format($med->selling_price, 2) }}</td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                @if ($med->expiry_date)
                                    <span @class(['', 'text-red-500' => $med->expiry_date->isPast(), 'text-amber-500' => $med->expiry_date->diffInDays() < 90 && !$med->expiry_date->isPast()])>
                                        {{ $med->expiry_date->format('M Y') }}
                                    </span>
                                @else —
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span @class(['text-xs px-2.5 py-0.5 rounded-full font-medium',
                                              'bg-green-100 text-green-700' => $med->is_active,
                                              'bg-slate-100 text-slate-500' => !$med->is_active])>
                                    {{ $med->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <button wire:click="openEdit({{ $med->id }})" class="p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 transition-colors">
                                    <flux:icon name="pencil" class="w-4 h-4" />
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-16 text-center text-slate-500 text-sm">
                                No medicines found. <button wire:click="openCreate" class="text-emerald-600 underline">Add your first medicine</button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-800">{{ $medicines->links() }}</div>
    </div>

    {{-- Slide-over form --}}
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex justify-end bg-black/40 backdrop-blur-sm" wire:click="$set('showForm',false)">
            <div class="w-full max-w-lg bg-white dark:bg-slate-900 h-full overflow-y-auto shadow-2xl" wire:click.stop>
                <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-slate-800">
                    <h2 class="font-bold text-slate-900 dark:text-white">{{ $editingId ? 'Edit' : 'Add' }} Medicine</h2>
                    <button wire:click="$set('showForm',false)" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                        <flux:icon name="x-mark" class="w-4 h-4" />
                    </button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Medicine Name *</flux:label>
                            <flux:input wire:model="name" placeholder="Paracetamol" class="mt-1 w-full rounded-xl" />
                            @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Generic Name</flux:label>
                            <flux:input wire:model="generic_name" placeholder="Acetaminophen" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Brand Name</flux:label>
                            <flux:input wire:model="brand_name" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Type *</flux:label>
                            <flux:select wire:model="type" class="mt-1 w-full rounded-xl">
                                @foreach (['tablet','capsule','syrup','injection','ointment','drops','inhaler','powder','consumable','other'] as $t)
                                    <option value="{{ $t }}">{{ ucfirst($t) }}</option>
                                @endforeach
                            </flux:select>
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
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Strength</flux:label>
                            <flux:input wire:model="strength" placeholder="500mg" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Unit</flux:label>
                            <flux:input wire:model="unit" placeholder="mg, ml…" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Current Stock *</flux:label>
                            <flux:input wire:model="current_stock" type="number" min="0" class="mt-1 w-full rounded-xl" />
                            @error('current_stock') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Reorder Level</flux:label>
                            <flux:input wire:model="reorder_level" type="number" min="0" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Purchase Price (₹)</flux:label>
                            <flux:input wire:model="purchase_price" type="number" min="0" step="0.01" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Selling Price (₹) *</flux:label>
                            <flux:input wire:model="selling_price" type="number" min="0" step="0.01" class="mt-1 w-full rounded-xl" />
                            @error('selling_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">MRP (₹)</flux:label>
                            <flux:input wire:model="mrp" type="number" min="0" step="0.01" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Batch Number</flux:label>
                            <flux:input wire:model="batch_number" class="mt-1 w-full rounded-xl font-mono" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Expiry Date</flux:label>
                            <flux:input wire:model="expiry_date" type="date" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div>
                            <flux:label class="text-sm text-slate-600 dark:text-slate-300">Manufacturer</flux:label>
                            <flux:input wire:model="manufacturer" class="mt-1 w-full rounded-xl" />
                        </div>
                        <div class="col-span-2 flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                            <input type="checkbox" wire:model="is_active" class="w-4 h-4 rounded text-emerald-600" id="med-active" />
                            <label for="med-active" class="text-sm text-slate-700 dark:text-slate-300">Active</label>
                        </div>
                    </div>
                    <flux:button wire:click="save" class="w-full bg-emerald-600 text-white border-0 rounded-xl">
                        {{ $editingId ? 'Update' : 'Add' }} Medicine
                    </flux:button>
                </div>
            </div>
        </div>
    @endif

</div>
