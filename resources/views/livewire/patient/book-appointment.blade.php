<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 border border-slate-800 p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-500 mb-3">
                    <span class="text-blue-500">{{ $clinic->name }}</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    <span>Secure Booking</span>
                </nav>
                <h1 class="text-4xl font-black text-white tracking-tight">Reserve Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Consultation</span></h1>
                <p class="text-slate-400 mt-2 font-medium">Select a department and professional to schedule your visit.</p>
            </div>
            <div>
                <a href="{{ route('patient.browse-clinics') }}" wire:navigate class="px-6 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl font-bold transition-all border border-slate-700 flex items-center gap-2">
                    <x-icons name="arrow-left" class="w-5 h-5" />
                    Back to Clinics
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Booking Form -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 shadow-xl">
                <form wire:submit="book" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Department Selection -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Specialized Department</label>
                            <select wire:model.live="selectedDepartment" class="w-full bg-slate-800 border-slate-700 text-white rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 transition-all">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Doctor Selection -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Medical Professional</label>
                            <select wire:model.live="selectedDoctor" class="w-full bg-slate-800 border-slate-700 text-white rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 transition-all @error('selectedDoctor') border-red-500 @enderror">
                                <option value="">Select a Doctor</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}">Dr. {{ $doc->user->name }} ({{ $doc->department->name ?? 'General' }})</option>
                                @endforeach
                            </select>
                            @error('selectedDoctor') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date Selection -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Preferred Date</label>
                            <input type="date" wire:model.live="selectedDate" min="{{ date('Y-m-d') }}" class="w-full bg-slate-800 border-slate-700 text-white rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 transition-all @error('selectedDate') border-red-500 @enderror">
                            @error('selectedDate') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <!-- Time Selection -->
                        <div class="space-y-2">
                            <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Available Time Slots</label>
                            <div class="grid grid-cols-3 gap-2">
                                @forelse($availableTimes as $time)
                                    <button type="button" 
                                            wire:click="$set('selectedTime', '{{ $time }}')"
                                            class="py-2 rounded-xl text-xs font-bold transition-all border {{ $selectedTime === $time ? 'bg-blue-600 border-blue-500 text-white shadow-lg' : 'bg-slate-800 border-slate-700 text-slate-400 hover:border-blue-500/50 hover:text-white' }}">
                                        {{ $time }}
                                    </button>
                                @empty
                                    <p class="col-span-full text-slate-600 text-[10px] italic py-2">Select a doctor and date to see availability.</p>
                                @endforelse
                            </div>
                            @error('selectedTime') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <!-- Chief Complaint -->
                    <div class="space-y-2">
                        <label class="text-xs font-black uppercase tracking-widest text-slate-500 ml-1">Reason for Visit (Chief Complaint)</label>
                        <textarea wire:model="chiefComplaint" rows="4" placeholder="Briefly describe your symptoms or reason for the appointment..." class="w-full bg-slate-800 border-slate-700 text-white rounded-3xl p-6 focus:ring-2 focus:ring-blue-500 transition-all @error('chiefComplaint') border-red-500 @enderror"></textarea>
                        @error('chiefComplaint') <p class="text-red-500 text-[10px] font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-500 text-white rounded-3xl font-black text-lg transition-all shadow-2xl active:scale-[0.98] flex items-center justify-center gap-3 group">
                            <span>Request Appointment</span>
                            <x-icons name="check-circle" class="w-6 h-6 group-hover:scale-110 transition-transform" />
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Clinic Info Sidebar -->
        <div class="space-y-8">
            <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 shadow-xl text-center">
                <div class="w-32 h-32 rounded-[2.5rem] bg-slate-800 border border-slate-700 p-4 mx-auto mb-6 flex items-center justify-center shadow-inner overflow-hidden">
                    @if($clinic->logo)
                        <img src="{{ asset('storage/' . $clinic->logo) }}" alt="{{ $clinic->name }}" class="w-full h-full object-cover">
                    @else
                        <x-icons name="hospital" class="w-16 h-16 text-slate-600" />
                    @endif
                </div>
                <h3 class="text-2xl font-black text-white">{{ $clinic->name }}</h3>
                <p class="text-slate-500 font-medium mt-1 leading-relaxed px-4">
                    {{ $clinic->city }}, {{ $clinic->country }}
                </p>
                
                <div class="mt-8 pt-8 border-t border-slate-800 space-y-4">
                    <div class="flex items-start gap-4 text-left p-4 bg-slate-800/30 rounded-2xl border border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center shrink-0">
                            <x-icons name="clock" class="w-5 h-5 text-blue-500" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Working Hours</p>
                            <p class="text-white text-sm font-bold mt-0.5">09:00 AM - 05:00 PM</p>
                            <p class="text-slate-500 text-[10px]">Mon - Fri</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 text-left p-4 bg-slate-800/30 rounded-2xl border border-slate-800">
                        <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center shrink-0">
                            <x-icons name="credit-card" class="w-5 h-5 text-orange-500" />
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Consultation Fee</p>
                            <p class="text-white text-sm font-bold mt-0.5">Starting from $50.00</p>
                            <p class="text-slate-500 text-[10px]">Payable at reception</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-600 rounded-[2.5rem] p-8 text-white relative overflow-hidden group shadow-2xl">
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-110 transition-transform"></div>
                <h4 class="text-lg font-bold mb-3 relative text-white">Need Assistance?</h4>
                <p class="text-blue-100 text-sm leading-relaxed relative">If you're experiencing an emergency or need immediate help selecting a specialist, please call the clinic directly at:</p>
                <div class="mt-6 font-black text-2xl relative tracking-tight">{{ $clinic->phone }}</div>
            </div>
        </div>
    </div>
</div>
