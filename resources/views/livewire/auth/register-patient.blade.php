<div class="p-8">

    <div class="mb-6">
        <a href="{{ route('register') }}" wire:navigate
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white text-sm mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </a>
        <h1 class="text-2xl font-bold text-white">Patient Registration</h1>
        <p class="text-slate-400 text-sm mt-1">Create your free patient account</p>
    </div>

    <form wire:submit="register" class="space-y-4">

        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <flux:label class="text-slate-300 text-sm">Full Name</flux:label>
                <flux:input wire:model="name" placeholder="Jane Doe"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl" />
                @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <flux:label class="text-slate-300 text-sm">Email Address</flux:label>
                <flux:input wire:model="email" type="email" placeholder="jane@example.com"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl" />
                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <flux:label class="text-slate-300 text-sm">Phone Number</flux:label>
                <flux:input wire:model="phone" type="tel" placeholder="+1 (555) 000-0000"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl" />
                @error('phone') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <flux:label class="text-slate-300 text-sm">Gender</flux:label>
                <flux:select wire:model="gender"
                             class="mt-1 w-full bg-white/5 border-white/10 text-white rounded-xl">
                    <option value="">Select…</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                    <option value="prefer_not_to_say">Prefer not to say</option>
                </flux:select>
                @error('gender') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <flux:label class="text-slate-300 text-sm">Blood Group</flux:label>
                <flux:select wire:model="blood_group"
                             class="mt-1 w-full bg-white/5 border-white/10 text-white rounded-xl">
                    <option value="">Unknown</option>
                    @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                        <option value="{{ $bg }}">{{ $bg }}</option>
                    @endforeach
                </flux:select>
            </div>

            <div class="col-span-2">
                <flux:label class="text-slate-300 text-sm">Date of Birth</flux:label>
                <flux:input wire:model="date_of_birth" type="date"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white rounded-xl" />
                @error('date_of_birth') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <flux:label class="text-slate-300 text-sm">Password</flux:label>
                <flux:input wire:model="password" type="password" placeholder="Min 8 chars"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl"
                            viewable />
                @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-2">
                <flux:label class="text-slate-300 text-sm">Confirm Password</flux:label>
                <flux:input wire:model="password_confirmation" type="password" placeholder="Repeat password"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl"
                            viewable />
            </div>
        </div>

        {{-- Terms --}}
        <div class="flex gap-3 p-3 rounded-xl bg-white/3 border border-white/10">
            <flux:checkbox wire:model="terms" id="patient-terms"
                           class="mt-0.5 border-white/20 bg-white/5 checked:bg-emerald-500 flex-shrink-0" />
            <label for="patient-terms" class="text-sm text-slate-400 cursor-pointer">
                I agree to the
                <a href="#" class="text-emerald-400 hover:underline">Terms</a> and
                <a href="#" class="text-emerald-400 hover:underline">Privacy Policy</a>
            </label>
        </div>
        @error('terms') <p class="text-xs text-red-400">{{ $message }}</p> @enderror

        <flux:button type="submit" variant="primary"
                     class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 border-0 text-white font-semibold py-2.5 rounded-xl shadow-lg shadow-emerald-500/20"
                     wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="register">Create Patient Account</span>
            <span wire:loading wire:target="register">Creating account…</span>
        </flux:button>

    </form>

    <p class="text-center text-sm text-slate-400 mt-6">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="text-indigo-400 hover:text-indigo-300 font-medium">Sign in</a>
    </p>

</div>
