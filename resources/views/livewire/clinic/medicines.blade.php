<div class="space-y-6" x-data="{ showModal: @entangle('showModal'), showDeleteModal: @entangle('showDeleteModal') }">
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 mb-8">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-violet-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ $pageTitle }}</h1>
                <p class="text-slate-400">Manage pharmacy inventory and medicine stock levels</p>
            </div>
            <button wire:click="create" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-violet-600 hover:from-cyan-500 hover:to-violet-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-cyan-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Medicine
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Total Items</p>
            <p class="text-3xl font-black text-white">{{ $medicines->total() }}</p>
        </div>
        <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 text-rose-500">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Low Stock Alerts</p>
            @php
                $lowStockCount = \App\Models\Medicine::where('clinic_id', auth()->user()->clinic->id)
                    ->whereRaw('stock_quantity <= reorder_level')
                    ->count();
            @endphp
            <p class="text-3xl font-black">{{ $lowStockCount }}</p>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden">
        <div class="p-8 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-white">Stock Inventory</h3>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, category..." class="w-full md:w-80 pl-10 pr-4 py-2.5 bg-slate-800 border-slate-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/30 text-slate-400 text-xs uppercase tracking-widest font-bold">
                        <th class="px-8 py-5">Medicine Details</th>
                        <th class="px-8 py-5">Category</th>
                        <th class="px-8 py-5">Stock Level</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($medicines as $med)
                        <tr class="hover:bg-slate-800/20 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 flex items-center justify-center border border-cyan-500/20">
                                        <x-icons name="pill" class="w-6 h-6 text-cyan-400" />
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold">{{ $med->name }}</p>
                                        <p class="text-slate-500 text-xs">{{ $med->generic_name }} ({{ $med->strength }})</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-800 text-slate-300 rounded-lg text-xs">{{ $med->category }}</span>
                                <p class="text-[10px] text-slate-500 mt-1 uppercase tracking-wider">{{ $med->dosage_form }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="{{ $med->stock_quantity <= $med->reorder_level ? 'text-rose-500' : 'text-slate-400' }} font-bold">
                                            {{ $med->stock_quantity }} units
                                        </span>
                                        <span class="text-slate-600">Min: {{ $med->reorder_level }}</span>
                                    </div>
                                    <div class="w-24 h-1.5 bg-slate-800 rounded-full overflow-hidden">
                                        @php
                                            $percent = min(100, ($med->stock_quantity / max(1, $med->reorder_level * 2)) * 100);
                                        @endphp
                                        <div class="h-full {{ $med->stock_quantity <= $med->reorder_level ? 'bg-rose-500' : 'bg-cyan-500' }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="edit({{ $med->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-cyan-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $med->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-rose-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center">
                                <p class="text-slate-400">No medical stock found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($medicines->hasPages())
            <div class="px-8 py-6 border-t border-slate-800 bg-slate-800/10">
                {{ $medicines->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showModal = false"></div>
            
            <div x-show="showModal" x-transition.scale.95 class="relative bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 max-w-2xl w-full shadow-2xl overflow-hidden">
                <div class="relative">
                    <h3 class="text-2xl font-bold text-white mb-6">{{ $medicineId ? 'Edit' : 'Add' }} Medicine</h3>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Medicine Name</label>
                                <input type="text" wire:model="name" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="e.g. Paracetamol">
                                @error('name') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Generic Name</label>
                                <input type="text" wire:model="generic_name" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                @error('generic_name') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Category</label>
                                <input type="text" wire:model="category" list="categories" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="e.g. Analgesic">
                                <datalist id="categories">
                                    <option value="Analgesic">
                                    <option value="Antibiotic">
                                    <option value="Antiviral">
                                    <option value="Antipyretic">
                                </datalist>
                                @error('category') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Dosage Form</label>
                                <select wire:model="dosage_form" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                    <option value="">Select Form</option>
                                    <option value="Tablet">Tablet</option>
                                    <option value="Capsule">Capsule</option>
                                    <option value="Syrup">Syrup</option>
                                    <option value="Injection">Injection</option>
                                    <option value="Ointment">Ointment</option>
                                </select>
                                @error('dosage_form') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Strength</label>
                                <input type="text" wire:model="strength" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="e.g. 500mg">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Unit Price ($)</label>
                                <input type="number" step="0.01" wire:model="price" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                @error('price') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Stock Quantity</label>
                                <input type="number" wire:model="stock_quantity" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                @error('stock_quantity') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Reorder Level</label>
                                <input type="number" wire:model="reorder_level" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                @error('reorder_level') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="showModal = false" class="flex-1 px-6 py-4 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                            <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-cyan-600 to-violet-600 hover:from-cyan-500 hover:to-violet-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-cyan-500/20 active:scale-95">
                                {{ $medicineId ? 'Update Stock' : 'Add to Stock' }}
                            </button>
                        </div>
                    </form>
                </div>
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
                <h3 class="text-xl font-bold text-white mb-2">Remove Medicine?</h3>
                <p class="text-slate-400 mb-8 text-sm">Are you sure you want to remove this medicine from inventory?</p>
                
                <div class="flex gap-4">
                    <button @click="showDeleteModal = false" class="flex-1 px-6 py-3 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                    <button wire:click="delete" class="flex-1 px-6 py-3 bg-rose-600 text-white rounded-2xl font-bold hover:bg-rose-500 transition-all">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
