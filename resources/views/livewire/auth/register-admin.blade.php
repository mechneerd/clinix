<div class="p-8">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('register') }}" wire:navigate
           class="inline-flex items-center gap-1.5 text-slate-400 hover:text-white text-sm mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back
        </a>
        <h1 class="text-2xl font-bold text-white">Healthcare Provider Registration</h1>
        <p class="text-slate-400 text-sm mt-1">Step {{ $step }} of {{ $totalSteps }}</p>
    </div>

    {{-- Progress Bar --}}
    <div class="mb-8">
        <div class="flex items-center gap-2 mb-3">
            @for ($i = 1; $i <= $totalSteps; $i++)
                <div class="flex items-center {{ $i < $totalSteps ? 'flex-1' : '' }}">
                    <div @class([
                        'w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all',
                        'bg-indigo-500 text-white shadow-lg shadow-indigo-500/30' => $step >= $i,
                        'bg-white/10 text-slate-500' => $step < $i,
                    ])>
                        @if ($step > $i)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        @else
                            {{ $i }}
                        @endif
                    </div>
                    @if ($i < $totalSteps)
                        <div @class([
                            'flex-1 h-0.5 mx-2 transition-all',
                            'bg-indigo-500' => $step > $i,
                            'bg-white/10' => $step <= $i,
                        ])></div>
                    @endif
                </div>
            @endfor
        </div>
        <div class="flex justify-between text-xs text-slate-500">
            <span>Account</span>
            <span>Professional</span>
            <span>Personal</span>
        </div>
    </div>

    {{-- ─── Step 1: Account ─────────────────────────────────────────────────── --}}
    @if ($step === 1)
    <form wire:submit="nextStep" class="space-y-4">

        <div class="grid grid-cols-1 gap-4">
            <div>
                <flux:label class="text-slate-300 text-sm">Full Name</flux:label>
                <flux:input wire:model="name" placeholder="Dr. John Smith"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl" />
                @error('name') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <flux:label class="text-slate-300 text-sm">Email Address</flux:label>
                <flux:input wire:model="email" type="email" placeholder="doctor@example.com"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl" />
                @error('email') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <flux:label class="text-slate-300 text-sm">Phone Number</flux:label>
                <flux:input wire:model="phone" type="tel" placeholder="+1 (555) 000-0000"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl" />
                @error('phone') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <flux:label class="text-slate-300 text-sm">Password</flux:label>
                <flux:input wire:model="password" type="password" placeholder="Min 8 chars, 1 uppercase, 1 number"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl"
                            viewable />
                @error('password') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <flux:label class="text-slate-300 text-sm">Confirm Password</flux:label>
                <flux:input wire:model="password_confirmation" type="password" placeholder="Repeat your password"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl"
                            viewable />
            </div>
        </div>

        <flux:button type="submit" variant="primary"
                     class="w-full bg-gradient-to-r from-indigo-500 to-violet-600 border-0 text-white font-semibold py-2.5 rounded-xl">
            Continue →
        </flux:button>

    </form>
    @endif

    {{-- ─── Step 2: Professional ────────────────────────────────────────────── --}}
    @if ($step === 2)
    <form wire:submit="nextStep" class="space-y-4">

        <div>
            <flux:label class="text-slate-300 text-sm">Medical License Number</flux:label>
            <flux:input wire:model="license_number" placeholder="e.g. MD-123456"
                        class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl" />
            @error('license_number') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <flux:label class="text-slate-300 text-sm">Specialty / Field</flux:label>
            <flux:select wire:model="specialty"
                         class="mt-1 w-full bg-white/5 border-white/10 text-white rounded-xl">
                <option value="">Select specialty…</option>
                @foreach([
                    'General Practice', 'Cardiology', 'Dermatology', 'Endocrinology',
                    'Gastroenterology', 'Neurology', 'Obstetrics & Gynecology',
                    'Oncology', 'Ophthalmology', 'Orthopedics', 'Pediatrics',
                    'Psychiatry', 'Radiology', 'Surgery', 'Urology',
                    'Clinic Administrator', 'Lab Manager', 'Pharmacy Manager', 'Other',
                ] as $spec)
                    <option value="{{ $spec }}">{{ $spec }}</option>
                @endforeach
            </flux:select>
            @error('specialty') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div>
            <flux:label class="text-slate-300 text-sm">Years of Experience</flux:label>
            <flux:input wire:model="years_of_experience" type="number" min="0" max="60" placeholder="0"
                        class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl" />
            @error('years_of_experience') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <flux:button type="button" wire:click="prevStep" variant="ghost"
                         class="flex-1 border border-white/10 text-slate-300 rounded-xl">
                ← Back
            </flux:button>
            <flux:button type="submit" variant="primary"
                         class="flex-1 bg-gradient-to-r from-indigo-500 to-violet-600 border-0 text-white font-semibold rounded-xl">
                Continue →
            </flux:button>
        </div>

    </form>
    @endif

    {{-- ─── Step 3: Personal + Terms ───────────────────────────────────────── --}}
    @if ($step === 3)
    <form wire:submit="register" class="space-y-4">

        <div class="grid grid-cols-2 gap-4">
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
                <flux:label class="text-slate-300 text-sm">Date of Birth</flux:label>
                <flux:input wire:model="date_of_birth" type="date"
                            class="mt-1 w-full bg-white/5 border-white/10 text-white rounded-xl" />
                @error('date_of_birth') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <flux:label class="text-slate-300 text-sm">Practice Address <span class="text-slate-500">(optional)</span></flux:label>
            <flux:textarea wire:model="address" placeholder="123 Medical Center Drive, City, State"
                           rows="2"
                           class="mt-1 w-full bg-white/5 border-white/10 text-white placeholder-slate-500 rounded-xl" />
        </div>

        {{-- Terms --}}
        <div class="p-4 rounded-xl bg-white/3 border border-white/10">
            <div class="flex gap-3">
                <flux:checkbox wire:model="terms" id="terms"
                               class="mt-0.5 border-white/20 bg-white/5 checked:bg-indigo-500 flex-shrink-0" />
                <label for="terms" class="text-sm text-slate-400 cursor-pointer leading-relaxed">
                    I agree to the
                    <a href="#" class="text-indigo-400 hover:underline">Terms of Service</a>
                    and
                    <a href="#" class="text-indigo-400 hover:underline">Privacy Policy</a>.
                    I confirm that my medical license information is accurate.
                </label>
            </div>
            @error('terms') <p class="mt-1 text-xs text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3 pt-2">
            <flux:button type="button" wire:click="prevStep" variant="ghost"
                         class="flex-1 border border-white/10 text-slate-300 rounded-xl">
                ← Back
            </flux:button>
            <flux:button type="submit" variant="primary"
                         class="flex-1 bg-gradient-to-r from-indigo-500 to-violet-600 border-0 text-white font-semibold rounded-xl"
                         wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="register">Create Account 🎉</span>
                <span wire:loading wire:target="register">Creating…</span>
            </flux:button>
        </div>

    </form>
    @endif

    {{-- Footer --}}
    <p class="text-center text-xs text-slate-500 mt-6">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="text-indigo-400 hover:text-indigo-300">Sign in</a>
    </p>

</div>
