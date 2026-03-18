<div class="space-y-6" x-data="{ showModal: @entangle('showModal'), showDeleteModal: @entangle('showDeleteModal') }">
    
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 mb-8">
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-64 h-64 bg-cyan-600/10 blur-[80px] rounded-full"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Service Packages</h1>
                <p class="text-slate-400 mt-2">Manage subscription plans and platform limits for all clinics</p>
            </div>
            
            <button wire:click="create" class="px-6 py-3 bg-gradient-to-r from-cyan-500 to-violet-600 text-white rounded-2xl font-semibold shadow-xl shadow-cyan-500/10 hover:shadow-cyan-500/20 transition-all flex items-center gap-2 group">
                <svg class="w-5 h-5 group-hover:rotate-90 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create New Package
            </button>
        </div>
    </div>

    <!-- Packages Table -->
    <div class="bg-slate-900/60 border border-slate-800 rounded-3xl overflow-hidden backdrop-blur-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-800/30">
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest">Package Details</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Limits</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Price</th>
                        <th class="px-6 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-5 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($packages as $package)
                    <tr class="hover:bg-slate-800/20 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-800 border border-slate-700 flex items-center justify-center text-cyan-400 group-hover:border-cyan-500/50 transition-all">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                </div>
                                <div>
                                    <p class="font-bold text-white text-lg leading-tight">{{ $package->name }}</p>
                                    <p class="text-slate-500 text-xs mt-1">{{ Str::limit($package->description, 50) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-6 border-x border-slate-800/30">
                            <div class="flex flex-wrap justify-center gap-2 max-w-[200px] mx-auto">
                                <span class="px-2 py-0.5 rounded-lg bg-slate-800 text-slate-400 text-[10px] font-bold uppercase transition-colors group-hover:text-cyan-400">{{ $package->max_doctors }} Doctors</span>
                                <span class="px-2 py-0.5 rounded-lg bg-slate-800 text-slate-400 text-[10px] font-bold uppercase transition-colors group-hover:text-cyan-400">{{ $package->max_clinics }} Clinics</span>
                                @if($package->white_label)
                                    <span class="px-2 py-0.5 rounded-lg bg-violet-500/10 text-violet-400 text-[10px] font-bold uppercase">White Label</span>
                                @endif
                                @if($package->telemedicine)
                                    <span class="px-2 py-0.5 rounded-lg bg-emerald-500/10 text-emerald-400 text-[10px] font-bold uppercase">Telemedicine</span>
                                @endif
                                @if($package->api_access)
                                    <span class="px-2 py-0.5 rounded-lg bg-cyan-500/10 text-cyan-400 text-[10px] font-bold uppercase">API Access</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <p class="text-white font-bold text-lg">${{ number_format($package->price, 2) }}</p>
                            <p class="text-slate-500 text-[10px] uppercase font-bold tracking-tighter">{{ $package->billing_cycle }}</p>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <button wire:click="toggleStatus({{ $package->id }})" class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition-all {{ $package->is_active ? 'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white' : 'bg-rose-500/10 text-rose-500 hover:bg-rose-500 hover:text-white' }}">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </button>
                                @if(!$package->is_approved)
                                    <button wire:click="approve({{ $package->id }})" class="px-3 py-1 rounded-full bg-amber-500/10 text-amber-500 text-[10px] font-bold uppercase tracking-wider hover:bg-amber-500 hover:text-white transition-all">Pending Approval</button>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-cyan-500/10 text-cyan-500 text-[10px] font-bold uppercase tracking-wider">Approved</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button wire:click="edit({{ $package->id }})" class="p-2 bg-slate-800 text-slate-400 rounded-xl hover:bg-cyan-500 hover:text-white transition-all" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $package->id }})" class="p-2 bg-slate-800 text-slate-400 rounded-xl hover:bg-rose-500 hover:text-white transition-all" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center justify-center opacity-40">
                                <svg class="w-20 h-20 text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                <p class="text-xl font-bold text-white">No Packages Found</p>
                                <p class="text-slate-400 mt-1">Start by creating your first subscription plan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($packages->hasPages())
        <div class="px-8 py-4 border-t border-slate-800">
            {{ $packages->links() }}
        </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showModal = false"></div>
            
            <div x-show="showModal" x-transition.scale.95 class="relative bg-slate-900 border border-slate-800 rounded-3xl shadow-2xl w-full max-w-4xl overflow-hidden backdrop-blur-xl">
                <div class="p-8 border-b border-slate-800 flex items-center justify-between bg-slate-800/20">
                    <div>
                        <h2 class="text-2xl font-bold text-white">{{ $packageId ? 'Edit' : 'Create' }} Package</h2>
                        <p class="text-slate-400 text-sm mt-1">Configure service limits and pricing</p>
                    </div>
                    <button @click="showModal = false" class="p-2 text-slate-500 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="p-8">
                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- Basic Info -->
                        <div class="space-y-6">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest border-b border-slate-800 pb-2">Basic Configuration</h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Package Name</label>
                                <input type="text" wire:model="name" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-cyan-500/50 focus:ring-4 focus:ring-cyan-500/10 transition-all" placeholder="e.g. Professional Plan">
                                @error('name') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Description</label>
                                <textarea wire:model="description" rows="3" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-cyan-500/50 focus:ring-4 focus:ring-cyan-500/10 transition-all" placeholder="Describe the plan benefits..."></textarea>
                                @error('description') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Price ($)</label>
                                    <input type="number" step="0.01" wire:model="price" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-cyan-500/50 focus:ring-4 focus:ring-cyan-500/10 transition-all">
                                    @error('price') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Billing Cycle</label>
                                    <select wire:model="billing_cycle" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white focus:border-cyan-500/50 focus:ring-4 focus:ring-cyan-500/10 transition-all">
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                    @error('billing_cycle') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-400 mb-2">Duration (Days)</label>
                                <input type="number" wire:model="duration_days" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white">
                                @error('duration_days') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Limits -->
                        <div class="space-y-6">
                            <h3 class="text-sm font-bold text-slate-500 uppercase tracking-widest border-b border-slate-800 pb-2">Service Limits</h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Max Clinics</label>
                                    <input type="number" wire:model="max_clinics" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white">
                                    @error('max_clinics') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Max Doctors</label>
                                    <input type="number" wire:model="max_doctors" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white">
                                    @error('max_doctors') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Max Patients/Mo</label>
                                    <input type="number" wire:model="max_patients_per_month" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white" placeholder="0 for unlimited">
                                    @error('max_patients_per_month') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-400 mb-2">Storage (MB)</label>
                                    <input type="number" wire:model="storage_limit_mb" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white">
                                    @error('storage_limit_mb') <span class="text-rose-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Features Toggles -->
                            <div class="grid grid-cols-2 gap-y-4 pt-4">
                                @foreach([
                                    'api_access' => 'API Access', 
                                    'white_label' => 'White Label', 
                                    'telemedicine' => 'Telemedicine', 
                                    'advanced_reporting' => 'Reports',
                                    'is_active' => 'Active Status', 
                                    'is_approved' => 'Approved'
                                ] as $key => $label)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <div class="relative inline-flex items-center">
                                        <input type="checkbox" wire:model="{{ $key }}" class="sr-only peer">
                                        <div class="w-10 h-5 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-cyan-500"></div>
                                    </div>
                                    <span class="text-xs font-medium text-slate-400 group-hover:text-white transition-colors">{{ $label }}</span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-800 pt-8">
                        <button type="button" @click="showModal = false" class="px-6 py-3 text-slate-400 hover:text-white font-medium transition-colors">Cancel</button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-cyan-500 to-violet-600 text-white rounded-2xl font-bold shadow-xl shadow-cyan-500/10 hover:shadow-cyan-500/20 transition-all">
                            {{ $packageId ? 'Update Package' : 'Create Package' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-slate-950/90 backdrop-blur-md" @click="showDeleteModal = false"></div>
            
            <div x-show="showDeleteModal" x-transition.scale.95 class="relative bg-slate-900 border border-slate-800 rounded-3xl p-8 max-w-md w-full text-center overflow-hidden">
                <div class="w-20 h-20 bg-rose-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Delete Package?</h2>
                <p class="text-slate-400 mb-8 text-sm">This action cannot be undone. All data related to this package will be removed from the platform.</p>
                
                <div class="flex gap-4">
                    <button @click="showDeleteModal = false" class="flex-1 px-6 py-3 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                    <button wire:click="delete" class="flex-1 px-6 py-3 bg-rose-600 text-white rounded-2xl font-bold hover:bg-rose-500 transition-all shadow-xl shadow-rose-500/20">Delete Forever</button>
                </div>
            </div>
        </div>
    </div>
</div>