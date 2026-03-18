<div class="space-y-8 pb-20">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Ward & Admissions</h2>
            <p class="text-slate-500 text-sm">Real-time bed management and inpatient census.</p>
        </div>
        <button wire:click="openAdmissionModal" class="px-6 py-2.5 bg-brand-teal text-white rounded-xl font-bold shadow-lg shadow-brand-teal/20 hover:scale-[1.02] transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Admit New Patient
        </button>
    </div>

    <!-- Filters & Tabs -->
    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
        <div class="flex gap-1 p-1 bg-slate-100 dark:bg-slate-800 rounded-2xl w-fit">
            <button wire:click="$set('activeTab', 'grid')" class="px-6 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'grid' ? 'bg-white dark:bg-slate-700 text-brand-teal shadow-md' : 'text-slate-500 hover:text-slate-700' }}">Visual Occupancy</button>
            <button wire:click="$set('activeTab', 'list')" class="px-6 py-2 rounded-xl text-sm font-bold transition-all {{ $activeTab === 'list' ? 'bg-white dark:bg-slate-700 text-brand-teal shadow-md' : 'text-slate-500 hover:text-slate-700' }}">Admission Log</button>
        </div>

        <div class="w-full md:w-64">
            <select wire:model.live="ward_id" class="w-full h-11 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl px-4 text-sm font-medium focus:ring-2 focus:ring-brand-teal outline-none transition-all">
                <option value="">All Wards</option>
                @foreach($wards as $ward)
                    <option value="{{ $ward->id }}">{{ $ward->name }} ({{ $ward->department }})</option>
                @endforeach
            </select>
        </div>
    </div>

    @if($activeTab === 'grid')
        <!-- Ward Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($rooms as $room)
                <div class="group relative bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 rounded-2xl {{ !$room->is_occupied ? 'bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600' : 'bg-rose-100 dark:bg-rose-500/10 text-rose-600' }} flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <div class="flex flex-col items-end gap-1">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border {{ !$room->is_occupied ? 'border-emerald-100 text-emerald-600' : 'border-rose-100 text-rose-600' }}">
                                {{ $room->is_occupied ? 'Occupancy High' : 'Available' }}
                            </span>
                            @if($room->ward)
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">{{ $room->ward->name }}</span>
                            @endif
                        </div>
                    </div>

                    <h3 class="text-xl font-black text-slate-800 dark:text-white mb-1">Room {{ $room->room_number }}</h3>
                    <div class="flex items-center gap-2 mb-4">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $room->type }}</p>
                        <span class="w-1 h-1 bg-slate-200 dark:bg-slate-700 rounded-full"></span>
                        <p class="text-[10px] text-brand-teal font-bold uppercase tracking-widest">{{ $room->beds->count() }} Beds</p>
                    </div>

                    @if($room->is_occupied)
                        <div class="mt-8 pt-6 border-t border-slate-50 dark:border-slate-800 space-y-4">
                            @foreach($room->activeAdmissions as $adm)
                            <div class="flex items-center justify-between group/item">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-[10px] font-bold uppercase">
                                        {{ substr($adm->patient->first_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-900 dark:text-white">{{ $adm->patient->full_name }}</p>
                                        <p class="text-[9px] text-slate-400 font-medium">Bed: {{ $adm->bed->bed_number ?? 'General' }}</p>
                                    </div>
                                </div>
                                <button wire:click="dischargePatient({{ $adm->id }})" class="p-2 text-slate-300 hover:text-rose-500 transition-colors opacity-0 group-hover/item:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="mt-12">
                            <button wire:click="openAdmissionModal({{ $room->id }})" class="w-full py-3 bg-brand-teal text-white rounded-2xl text-[10px] font-bold opacity-0 group-hover:opacity-100 transition-all shadow-lg shadow-brand-teal/20 uppercase tracking-widest">
                                Quick Admission
                            </button>
                        </div>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white dark:bg-slate-900 rounded-[3rem] border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <p class="text-slate-400 font-medium">No rooms found for the selected ward.</p>
                </div>
            @endforelse
        </div>
    @else
        <!-- Admission Log (List View) -->
        <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
             <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Patient</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Location</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Admitted By</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Timeline</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @foreach($admissions as $adm)
                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-900 dark:text-white">{{ $adm->patient->full_name }}</span>
                            <span class="text-[10px] text-slate-400 block">{{ $adm->patient->patient_code }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-sm text-slate-700 dark:text-slate-300">Room {{ $adm->room->room_number }}</span>
                                <span class="text-[10px] text-brand-teal font-medium uppercase">Bed: {{ $adm->bed->bed_number ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                             <span class="text-sm font-medium text-slate-600">{{ $adm->admittedBy->name }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm font-medium text-slate-900 dark:text-white block text-sm">{{ $adm->admitted_at->format('d M Y') }}</span>
                            <span class="text-[10px] text-slate-400">Time: {{ $adm->admitted_at->format('H:i') }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider {{ $adm->status === 'admitted' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-600' }}">
                                {{ $adm->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-6">
                {{ $admissions->links() }}
            </div>
        </div>
    @endif

    <!-- Admission Modal -->
    @if($showAdmissionModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm animate-in fade-in duration-300">
        <div class="bg-white dark:bg-slate-900 w-full max-w-lg rounded-[2.5rem] shadow-2xl overflow-hidden border border-slate-200 dark:border-slate-800 animate-in zoom-in-95 duration-300">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50/50 dark:bg-slate-800/50">
                <h3 class="text-xl font-black text-slate-800 dark:text-white">Patient Admission</h3>
                <button wire:click="$set('showAdmissionModal', false)" class="text-slate-400 hover:text-rose-500 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-8 space-y-6">
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Select Patient</label>
                    <select wire:model="patient_id" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all">
                        <option value="">Choose Patient...</option>
                        @foreach($availablePatients as $p)
                            <option value="{{ $p->id }}">{{ $p->full_name }} ({{ $p->patient_code }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Room</label>
                        <select wire:model.live="room_id" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all">
                            <option value="">Select Room...</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">Room {{ $r->room_number }} ({{ $r->beds->count() }} Beds)</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Bed</label>
                        <select wire:model="bed_id" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all">
                            <option value="">Select Bed...</option>
                            @foreach($availableBeds as $b)
                                <option value="{{ $b->id }}">Bed {{ $b->bed_number }} ({{ $b->type }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Reason for Admission</label>
                    <textarea wire:model="reason" rows="3" class="w-full bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 py-3 focus:ring-2 focus:ring-brand-teal transition-all" placeholder="Enter clinical reason..."></textarea>
                </div>
            </div>

            <div class="p-8 bg-slate-50 dark:bg-slate-800/50 flex gap-4">
                <button wire:click="$set('showAdmissionModal', false)" class="flex-1 py-3 text-slate-500 font-bold hover:text-slate-700 transition-all">Cancel</button>
                <button wire:click="admitPatient" class="flex-1 py-3 bg-brand-teal text-white rounded-2xl font-bold shadow-lg shadow-brand-teal/20 hover:scale-[1.02] transition-all">Finalize Admission</button>
            </div>
        </div>
    </div>
    @endif
</div>
