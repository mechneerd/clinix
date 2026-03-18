<div class="space-y-6" x-data="{ showModal: @entangle('showModal'), showDeleteModal: @entangle('showDeleteModal') }">
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 mb-8">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-violet-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ $pageTitle }}</h1>
                <p class="text-slate-400">Manage nursing, administrative, and technical personnel</p>
            </div>
            <button wire:click="create" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-violet-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Staff Member
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden">
        <div class="p-8 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-white">Clinic Personnel</h3>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, role, ID..." class="w-full md:w-80 pl-10 pr-4 py-2.5 bg-slate-800 border-slate-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/30 text-slate-400 text-xs uppercase tracking-widest font-bold">
                        <th class="px-8 py-5">Staff Info</th>
                        <th class="px-8 py-5">Role</th>
                        <th class="px-8 py-5">Department</th>
                        <th class="px-8 py-5">Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($staffMembers as $staff)
                        <tr class="hover:bg-slate-800/20 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold">{{ $staff->user->name }}</p>
                                        <p class="text-slate-500 text-xs">{{ $staff->employee_id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-3 py-1 bg-slate-800 text-slate-300 rounded-lg text-xs">{{ $staff->role_display }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-slate-400 text-sm">{{ $staff->department?->name ?? 'Unassigned' }}</span>
                            </td>
                            <td class="px-8 py-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $staff->is_active ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-800 text-slate-500' }}">
                                    {{ $staff->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="startChat({{ $staff->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-violet-400 rounded-xl transition-colors border border-slate-700" title="Send Message">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    </button>
                                    <button wire:click="edit({{ $staff->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-violet-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $staff->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-rose-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400">No staff members found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($staffMembers->hasPages())
            <div class="px-8 py-6 border-t border-slate-800 bg-slate-800/10">
                {{ $staffMembers->links() }}
            </div>
        @endif
    </div>

    <!-- Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showModal = false"></div>
            
            <div x-show="showModal" x-transition.scale.95 class="relative bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 max-w-2xl w-full shadow-2xl">
                <h3 class="text-2xl font-bold text-white mb-6">{{ $staffId ? 'Edit' : 'Add' }} Staff Member</h3>
                
                <form wire:submit="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Full Name</label>
                            <input type="text" wire:model="name" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                            @error('name') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Email</label>
                            <input type="email" wire:model="email" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                            @error('email') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>

                         <div class="space-y-2">
                             @livewire('components.international-phone-input', ['phone' => $phone, 'country_id' => $country_id])
                         </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Password</label>
                            <input type="password" wire:model="password" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                            @error('password') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Role</label>
                            <select wire:model="role" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                <option value="">Select Role</option>
                                <option value="nurse">Nurse</option>
                                <option value="receptionist">Receptionist</option>
                                <option value="lab_worker">Lab Technician</option>
                                <option value="pharmacy_worker">Pharmacy Assistant</option>
                                <option value="lab_manager">Lab Manager</option>
                                <option value="pharmacy_manager">Pharmacy Manager</option>
                                <option value="reception_manager">Reception Manager</option>
                            </select>
                            @error('role') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Department</label>
                            <select wire:model="department_id" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Employee ID</label>
                            <input type="text" wire:model="employee_id" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                            @error('employee_id') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2 space-y-4">
                            <label class="text-sm font-medium text-slate-400 ml-1 block mb-2">Detailed Address</label>
                            @livewire('components.address-selector', [
                                'country_id' => $country_id,
                                'region_id' => $region_id,
                                'subregion_id' => $subregion_id,
                                'city_id' => $city_id,
                                'area_id' => $area_id
                            ], key('staff-address-'.($staffId ?? 'new')))
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium text-slate-400 ml-1">Qualification</label>
                            <input type="text" wire:model="qualification" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button type="button" @click="showModal = false" class="flex-1 px-6 py-4 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                        <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-500 hover:to-indigo-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-violet-500/20 active:scale-95">Save Staff Member</button>
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
                <h3 class="text-xl font-bold text-white mb-2">Remove Staff Member?</h3>
                <p class="text-slate-400 mb-8 text-sm">Are you sure you want to remove this staff member from the clinic?</p>
                
                <div class="flex gap-4">
                    <button @click="showDeleteModal = false" class="flex-1 px-6 py-3 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                    <button wire:click="delete" class="flex-1 px-6 py-3 bg-rose-600 text-white rounded-2xl font-bold hover:bg-rose-500 transition-all shadow-xl shadow-rose-500/20">Delete Forever</button>
                </div>
            </div>
        </div>
    </div>
</div>
