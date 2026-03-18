<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-slate-900 overflow-y-auto">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </div>
        </div>
        <h2 class="text-center text-3xl font-black text-white tracking-tight">Complete Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Profile</span></h2>
        <p class="mt-2 text-center text-sm text-slate-400 font-medium">Please provide a few more details to set up your clinical account.</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl px-4 md:px-0">
        <div class="bg-slate-800 border border-slate-700 py-8 px-4 shadow-2xl rounded-3xl sm:px-10">
            <form wire:submit="save" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">First Name</label>
                        <input type="text" wire:model="first_name" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('first_name') <p class="mt-1 text-xs text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <!-- Last Name -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Last Name</label>
                        <input type="text" wire:model="last_name" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('last_name') <p class="mt-1 text-xs text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Phone Number</label>
                        <input type="text" wire:model="phone" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition-all" placeholder="+1 234 567 890">
                        @error('phone') <p class="mt-1 text-xs text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <!-- DOB -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Date of Birth</label>
                        <input type="date" wire:model="date_of_birth" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('date_of_birth') <p class="mt-1 text-xs text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <!-- Gender -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Gender</label>
                        <select wire:model="gender" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition-all">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                        @error('gender') <p class="mt-1 text-xs text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <!-- Blood Group -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Blood Group</label>
                        <select wire:model="blood_group" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition-all">
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
                        @error('blood_group') <p class="mt-1 text-xs text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Residential Address</label>
                    <textarea wire:model="address" rows="3" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition-all" placeholder="House no, Street, City..."></textarea>
                    @error('address') <p class="mt-1 text-xs text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-700/50">
                    <!-- EC Name -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Emergency Contact Name</label>
                        <input type="text" wire:model="emergency_contact_name" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('emergency_contact_name') <p class="mt-1 text-xs text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>

                    <!-- EC Phone -->
                    <div>
                        <label class="block text-xs font-black uppercase tracking-widest text-slate-500 mb-2">Emergency Contact Phone</label>
                        <input type="text" wire:model="emergency_contact_phone" class="w-full bg-slate-900 border-slate-700 text-white rounded-xl p-3 focus:ring-2 focus:ring-blue-500 transition-all">
                        @error('emergency_contact_phone') <p class="mt-1 text-xs text-rose-500 font-bold uppercase">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-black text-lg transition-all shadow-xl active:scale-95 flex items-center justify-center gap-3">
                        Complete Registration
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
