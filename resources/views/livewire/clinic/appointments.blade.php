<div class="space-y-6" x-data="{ showModal: @entangle('showModal'), showDeleteModal: @entangle('showDeleteModal') }">
    <!-- Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-8 mb-8">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-violet-500/10 rounded-full blur-3xl"></div>
        
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">{{ $pageTitle }}</h1>
                <p class="text-slate-400">Schedule and manage patient appointments and clinical sessions</p>
            </div>
            <button wire:click="create" class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-cyan-600 to-violet-600 hover:from-cyan-500 hover:to-violet-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-cyan-500/20 active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Book Appointment
            </button>
        </div>
    </div>

    <!-- Filters & List -->
    <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] overflow-hidden">
        <div class="p-8 border-b border-slate-800 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex flex-col md:flex-row items-center gap-4">
                <div class="relative w-full md:w-auto">
                    <input type="date" wire:model.live="filterDate" class="w-full md:w-48 pl-4 pr-4 py-2.5 bg-slate-800 border-slate-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                </div>
                <div class="relative w-full md:w-80">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by patient name..." class="w-full pl-10 pr-4 py-2.5 bg-slate-800 border-slate-700 rounded-xl text-sm text-white focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                    <svg class="absolute left-3 top-3 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>
            
            <div class="flex items-center gap-4 text-sm text-slate-400">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-cyan-500"></span> Scheduled
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Checked In
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-slate-600"></span> Cancelled
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/30 text-slate-400 text-xs uppercase tracking-widest font-bold">
                        <th class="px-8 py-5">Time</th>
                        <th class="px-8 py-5">Patient</th>
                        <th class="px-8 py-5">Doctor</th>
                        <th class="px-8 py-5">Type / Status</th>
                        <th class="px-8 py-5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse ($appointments as $appointment)
                        <tr class="hover:bg-slate-800/20 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="text-white font-bold">{{ $appointment->start_time->format('h:i A') }}</div>
                                <div class="text-slate-500 text-xs">{{ $appointment->start_time->format('d M, Y') }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-cyan-500/10 flex items-center justify-center text-cyan-400 font-bold border border-cyan-500/20">
                                        {{ substr($appointment->patient->first_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-semibold">{{ $appointment->patient->full_name }}</p>
                                        <p class="text-slate-500 text-xs">{{ $appointment->patient->patient_code }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-300 text-sm">Dr. {{ $appointment->doctor->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col gap-1.5">
                                    <span class="text-slate-400 text-xs capitalize">{{ $appointment->type }}</span>
                                    <span class="inline-flex items-center w-max px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                        {{ $appointment->status === 'scheduled' ? 'bg-cyan-500/10 text-cyan-500' : '' }}
                                        {{ $appointment->status === 'checked_in' ? 'bg-emerald-500/10 text-emerald-500' : '' }}
                                        {{ $appointment->status === 'cancelled' ? 'bg-rose-500/10 text-rose-500' : '' }}
                                        {{ $appointment->status === 'completed' ? 'bg-slate-800 text-slate-400' : '' }}
                                    ">
                                        {{ str_replace('_', ' ', $appointment->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @if($appointment->status === 'scheduled')
                                    <button wire:click="checkIn({{ $appointment->id }})" class="px-3 py-1.5 bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white rounded-lg text-xs font-bold transition-all border border-emerald-500/20">
                                        Check In
                                    </button>
                                    @endif
                                    
                                    <button wire:click="edit({{ $appointment->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-cyan-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <button wire:click="confirmDelete({{ $appointment->id }})" class="p-2 bg-slate-800 text-slate-400 hover:text-rose-400 rounded-xl transition-colors border border-slate-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-10 h-10 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <p class="text-slate-400 font-medium">No appointments scheduled for this date</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
            <div class="px-8 py-6 border-t border-slate-800">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;" x-cloak>
        <div class="flex items-center justify-center min-h-screen p-4">
            <div x-show="showModal" x-transition.opacity class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showModal = false"></div>
            
            <div x-show="showModal" x-transition.scale.95 class="relative bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 max-w-2xl w-full shadow-2xl overflow-hidden">
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-64 h-64 bg-violet-500/10 rounded-full blur-3xl"></div>
                
                <div class="relative">
                    <h3 class="text-2xl font-bold text-white mb-8">{{ $appointmentId ? 'Edit' : 'Book New' }} Appointment</h3>
                    
                    <form wire:submit="save" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Select Patient</label>
                                <select wire:model="patient_id" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                    <option value="">Choose Patient...</option>
                                    @foreach($patients as $p)
                                        <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->patient_code }})</option>
                                    @endforeach
                                </select>
                                @error('patient_id') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Assign Doctor</label>
                                <select wire:model="doctor_id" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                    <option value="">Choose Doctor...</option>
                                    @foreach($doctors as $d)
                                        <option value="{{ $d->id }}">Dr. {{ $d->user->name }}</option>
                                    @endforeach
                                </select>
                                @error('doctor_id') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Appointment Date</label>
                                <input type="date" wire:model="appointment_date" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                @error('appointment_date') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-400 ml-1">Start Time</label>
                                    <input type="time" wire:model="start_time" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                    @error('start_time') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-slate-400 ml-1">End Time</label>
                                    <input type="time" wire:model="end_time" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                    @error('end_time') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Consultation Type</label>
                                <select wire:model="type" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                    <option value="consultation">Consultation</option>
                                    <option value="follow_up">Follow Up</option>
                                    <option value="emergency">Emergency</option>
                                    <option value="routine_checkup">Routine Checkup</option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Consultation Fee ($)</label>
                                <input type="number" step="0.01" wire:model="fee" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                @error('fee') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Chief Complaint</label>
                                <input type="text" wire:model="chief_complaint" placeholder="e.g. Severe headache, persistent cough" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all">
                                @error('chief_complaint') <span class="text-xs text-rose-500 mt-1 ml-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="md:col-span-2 space-y-2">
                                <label class="text-sm font-medium text-slate-400 ml-1">Administrative Notes</label>
                                <textarea wire:model="notes" rows="3" class="w-full bg-slate-800 border-slate-700 rounded-2xl text-white px-4 py-3 focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 transition-all"></textarea>
                            </div>
                        </div>

                        <div class="flex gap-4 pt-4">
                            <button type="button" @click="showModal = false" class="flex-1 px-6 py-4 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Cancel</button>
                            <button type="submit" class="flex-1 px-6 py-4 bg-gradient-to-r from-cyan-600 to-violet-600 hover:from-cyan-500 hover:to-violet-500 text-white rounded-2xl font-bold transition-all shadow-xl shadow-cyan-500/20 active:scale-95">
                                {{ $appointmentId ? 'Update Appointment' : 'Schedule Session' }}
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
                <h3 class="text-xl font-bold text-white mb-2">Cancel Appointment?</h3>
                <p class="text-slate-400 mb-8 text-sm">This will remove the appointment from the schedule. This action is irreversible.</p>
                
                <div class="flex gap-4">
                    <button @click="showDeleteModal = false" class="flex-1 px-6 py-3 bg-slate-800 text-white rounded-2xl font-bold border border-slate-700 hover:bg-slate-700 transition-all">Keep Appointment</button>
                    <button wire:click="delete" class="flex-1 px-6 py-3 bg-rose-600 text-white rounded-2xl font-bold hover:bg-rose-500 transition-all shadow-xl shadow-rose-500/20">Cancel Session</button>
                </div>
            </div>
        </div>
    </div>
</div>
