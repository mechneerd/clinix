<div class="space-y-6" x-data="{ showModal: @entangle('showModal'), showDeleteModal: @entangle('showDeleteModal') }">
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 mb-8">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ $pageTitle }}</h1>
                <p class="text-slate-400">Manage patient records and medical history</p>
            </div>
            <button wire:click="create" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-violet-600 hover:from-cyan-500 hover:to-violet-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-cyan-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Register Patient
            </button>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden">
        <div class="p-8 border-b border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-bold text-white">Patient Directory</h3>
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, code or email..." class="w-full md:w-80 pl-10 pr-4 py-2.5 bg-slate-800 border-slate-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/30 text-slate-400 text-xs uppercase tracking-widest font-bold">
                        <th class="px-8 py-5">Patient Details</th>
                        <th class="px-8 py-5">Contact</th>
                        <th class="px-8 py-5">Gender/Age</th>
                        <th class="px-8 py-5">Blood Group</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($patients as $patient)
                        <tr class="hover:bg-slate-800/20 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 flex items-center justify-center border border-cyan-500/20">
                                        <x-icons name="patient" class="w-6 h-6 text-cyan-400" />
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold">{{ $patient->full_name }}</p>
                                        <p class="text-slate-500 text-xs font-mono">{{ $patient->patient_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <p class="text-slate-300 text-sm">{{ $patient->email }}</p>
                                <p class="text-slate-500 text-xs">{{ $patient->phone }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-slate-300 text-sm capitalize">{{ $patient->gender }}</span>
                                <p class="text-slate-500 text-xs">{{ $patient->age }} years</p>
                            </td>
                            <td class="px-8 py-6">
                                @if($patient->blood_group)
                                <span class="px-3 py-1 bg-rose-500/10 text-rose-500 rounded-lg text-xs font-bold border border-rose-500/20">{{ $patient->blood_group }}</span>
                                @else
                                <span class="text-slate-600 text-xs">N/A</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click="startChat({{ $patient->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-emerald-400 rounded-xl transition-colors border border-slate-700" title="Send Message">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                    </button>
                                    <button wire:click="edit({{ $patient->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-cyan-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $patient->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-rose-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-500">
                                <p>No patients records found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($patients->hasPages())
            <div class="px-8 py-6 border-t border-slate-800">
                {{ $patients->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showModal = false"></div>
            
            <div x-show="showModal" x-transition.scale.95 class="relative bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 max-w-4xl w-full shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>
                
                <div class="relative">
                    <h3 class="text-2xl font-bold text-white mb-8">{{ $patientId ? 'Edit' : 'Register' }} Patient</h3>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">First Name</label>
                                <input type="text" wire:model="first_name" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                @error('first_name') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Last Name</label>
                                <input type="text" wire:model="last_name" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                @error('last_name') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Email</label>
                                <input type="email" wire:model="email" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                @error('email') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                             <div class="space-y-2 lg:col-span-1.5">
                                 @livewire('components.international-phone-input', ['phone' => $phone, 'country_id' => $country_id])
                             </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Date of Birth</label>
                                <input type="date" wire:model="date_of_birth" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                @error('date_of_birth') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Gender</label>
                                <select wire:model="gender" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Blood Group</label>
                                <select wire:model="blood_group" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                    <option value="">Unknown</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Emergency Contact Name</label>
                                <input type="text" wire:model="emergency_contact_name" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Emergency Phone</label>
                                <input type="text" wire:model="emergency_contact_phone" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                            </div>

                            <div class="md:col-span-3 space-y-4">
                                <label class="text-sm font-medium text-slate-400 ml-1 block mb-2">Detailed Address</label>
                                @livewire('components.address-selector', [
                                    'country_id' => $country_id,
                                    'region_id' => $region_id,
                                    'subregion_id' => $subregion_id,
                                    'city_id' => $city_id,
                                    'area_id' => $area_id
                                ], key('address-'.($patientId ?? 'new')))
                                
                                <textarea wire:model="address" rows="2" placeholder="Street name, building number, etc." class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all mt-4"></textarea>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Status</label>
                                <select wire:model="is_active" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="md:col-span-3 lg:col-span-1.5 space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Allergies (comma separated)</label>
                                <textarea wire:model="allergies" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="e.g. Penicillin, Peanuts"></textarea>
                            </div>

                            <div class="md:col-span-3 lg:col-span-1.5 space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Medical History (comma separated)</label>
                                <textarea wire:model="medical_history" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 transition-all" placeholder="e.g. Diabetes, Hypertension"></textarea>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="showModal = false" class="flex-1 px-6 py-4 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                            <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-cyan-600 to-violet-600 hover:from-cyan-500 hover:to-violet-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-cyan-500/20 active:scale-95">
                                {{ $patientId ? 'Save Changes' : 'Register Patient' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showDeleteModal" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showDeleteModal = false"></div>
            
            <div x-show="showDeleteModal" x-transition.scale.95 class="relative bg-slate-900 border border-slate-800 rounded-3xl p-8 max-w-md w-full text-center overflow-hidden">
                <div class="w-20 h-20 bg-rose-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Detach Patient?</h3>
                <p class="text-slate-400 mb-8 text-sm">This will remove the patient from your clinic directory. Their medical history records will remain in the system.</p>
                
                <div class="flex gap-4">
                    <button @click="showDeleteModal = false" class="flex-1 px-6 py-3 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                    <button wire:click="delete" class="flex-1 px-6 py-3 bg-rose-600 text-white rounded-2xl font-bold hover:bg-rose-500 transition-all shadow-xl shadow-rose-500/20">Detach Patient</button>
                </div>
            </div>
        </div>
    </div>
</div>
