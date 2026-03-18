<div class="space-y-6" x-data="{ showModal: @entangle('showModal'), showDeleteModal: @entangle('showDeleteModal') }">
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 mb-8">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ $pageTitle }}</h1>
                <p class="text-slate-400">Track and manage laboratory supplies, reagents, and consumables</p>
            </div>
            <button wire:click="create" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-emerald-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Consumable
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden">
        <div class="p-8 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-white">Lab Inventory</h3>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search supplies..." class="w-full md:w-80 pl-10 pr-4 py-2.5 bg-slate-800 border-slate-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/30 text-slate-400 text-xs uppercase tracking-widest font-bold">
                        <th class="px-8 py-5">Consumable Details</th>
                        <th class="px-8 py-5">Unit</th>
                        <th class="px-8 py-5">Stock Status</th>
                        <th class="px-8 py-5 text-right">Price</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($consumables as $item)
                        <tr class="hover:bg-slate-800/20 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center border border-emerald-500/20">
                                        <x-icons name="box" class="w-6 h-6 text-emerald-400" />
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold">{{ $item->name }}</p>
                                        <p class="text-slate-500 text-xs line-clamp-1 max-w-xs">{{ $item->description }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-slate-300 text-sm">
                                {{ $item->unit }}
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 h-1.5 bg-slate-800 rounded-full overflow-hidden min-w-[60px]">
                                        @php
                                            $percent = min(100, ($item->stock_quantity / max(1, $item->reorder_level * 3)) * 100);
                                            $color = $item->stock_quantity <= $item->reorder_level ? 'bg-rose-500' : 'bg-emerald-500';
                                        @endphp
                                        <div class="h-full {{ $color }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-sm font-bold {{ $item->stock_quantity <= $item->reorder_level ? 'text-rose-500' : 'text-slate-300' }}">
                                        {{ $item->stock_quantity }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <span class="text-slate-300 font-bold">${{ number_format($item->price, 2) }}</span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $item->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-emerald-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $item->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-rose-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400">No inventory items found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($consumables->hasPages())
            <div class="px-8 py-6 border-t border-slate-800 bg-slate-800/10">
                {{ $consumables->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showModal = false"></div>
            
            <div x-show="showModal" x-transition.scale.95 class="relative bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 max-w-lg w-full shadow-2xl">
                <h3 class="text-2xl font-bold text-white mb-6">{{ $consumableId ? 'Edit' : 'Add' }} Consumable</h3>
                
                <form wire:submit="save" class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">Item Name</label>
                        <input type="text" wire:model="name" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                        @error('name') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Unit</label>
                            <input type="text" wire:model="unit" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all" placeholder="e.g. Box of 50">
                            @error('unit') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Unit Price ($)</label>
                            <input type="number" step="0.01" wire:model="price" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            @error('price') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Stock Quantity</label>
                            <input type="number" wire:model="stock_quantity" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            @error('stock_quantity') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Reorder Level</label>
                            <input type="number" wire:model="reorder_level" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            @error('reorder_level') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-400 ml-1">Description</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all"></textarea>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 px-6 py-4 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-emerald-500/20 active:scale-95">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <div x-show="showDeleteModal" x-transition.scale.95 class="relative bg-slate-900 border border-slate-800 rounded-3xl p-8 max-w-md w-full text-center">
                <div class="w-20 h-20 bg-rose-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Remove Item?</h3>
                <p class="text-slate-400 mb-8 text-sm">Are you sure you want to remove this item from lab inventory?</p>
                
                <div class="flex gap-4">
                    <button @click="showDeleteModal = false" class="flex-1 px-6 py-3 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                    <button wire:click="delete" class="flex-1 px-6 py-3 bg-rose-600 text-white rounded-2xl font-bold hover:bg-rose-500 transition-all">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
