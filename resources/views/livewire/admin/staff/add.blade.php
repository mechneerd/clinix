<div class="p-6 lg:p-8 max-w-4xl mx-auto">

    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.staff.index', $clinic->id) }}" wire:navigate class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center hover:bg-slate-200 transition-colors">
            <flux:icon name="arrow-left" class="w-4 h-4" />
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Add Staff Member</h1>
            <p class="text-slate-500 text-sm">{{ $clinic->name }}</p>
        </div>
    </div>

    <div class="space-y-6">

        {{-- Role Selection --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
            <h2 class="font-semibold text-slate-900 dark:text-white mb-4">Select Role</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @php $roleConfig = [
                    'doctor'          => ['icon'=>'user', 'color'=>'indigo', 'label'=>'Doctor'],
                    'nurse'           => ['icon'=>'heart', 'color'=>'emerald', 'label'=>'Nurse'],
                    'lab_technician'  => ['icon'=>'beaker', 'color'=>'violet', 'label'=>'Lab Tech'],
                    'pharmacist'      => ['icon'=>'shopping-bag', 'color'=>'amber', 'label'=>'Pharmacist'],
                    'manager'         => ['icon'=>'briefcase', 'color'=>'blue', 'label'=>'Manager'],
                    'receptionist'    => ['icon'=>'computer-desktop', 'color'=>'teal', 'label'=>'Receptionist'],
                ] @endphp
                @foreach ($roles as $r)
                    @php $cfg = $roleConfig[$r] ?? ['icon'=>'user','color'=>'slate','label'=>ucfirst($r)] @endphp
                    <button wire:click="$set('role','{{ $r }}')"
                            @class([
                                'flex items-center gap-3 p-3 rounded-xl border-2 transition-all text-left',
                                'border-'.$cfg['color'].'-500 bg-'.$cfg['color'].'-50 dark:bg-'.$cfg['color'].'-900/20' => $role === $r,
                                'border-slate-200 dark:border-slate-700 hover:border-'.$cfg['color'].'-300' => $role !== $r,
                            ])>
                        <div @class(['w-9 h-9 rounded-lg flex items-center justify-center','bg-'.$cfg['color'].'-100 dark:bg-'.$cfg['color'].'-900/40'])>
                            <flux:icon :name="$cfg['icon']" class="w-5 h-5 text-{{ $cfg['color'] }}-600 dark:text-{{ $cfg['color'] }}-400" />
                        </div>
                        <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $cfg['label'] }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- Basic Info --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
            <h2 class="font-semibold text-slate-900 dark:text-white mb-4">Personal Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Full Name *</flux:label>
                    <flux:input wire:model="name" placeholder="Dr. John Smith" class="mt-1 w-full rounded-xl" />
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Email *</flux:label>
                    <flux:input wire:model="email" type="email" placeholder="staff@clinic.com" class="mt-1 w-full rounded-xl" />
                    @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Phone</flux:label>
                    <flux:input wire:model="phone" type="tel" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Gender</flux:label>
                    <flux:select wire:model="gender" class="mt-1 w-full rounded-xl">
                        <option value="">Select…</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </flux:select>
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Date of Birth</flux:label>
                    <flux:input wire:model="date_of_birth" type="date" class="mt-1 w-full rounded-xl" />
                </div>
            </div>
        </div>

        {{-- Professional Details --}}
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
            <h2 class="font-semibold text-slate-900 dark:text-white mb-4">Professional Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Department</flux:label>
                    <flux:select wire:model="department_id" class="mt-1 w-full rounded-xl">
                        <option value="">None</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </flux:select>
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Employment Type</flux:label>
                    <flux:select wire:model="employment_type" class="mt-1 w-full rounded-xl">
                        <option value="full_time">Full Time</option>
                        <option value="part_time">Part Time</option>
                        <option value="contract">Contract</option>
                        <option value="visiting">Visiting</option>
                    </flux:select>
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Qualification</flux:label>
                    <flux:input wire:model="qualification" placeholder="MBBS, MD, etc." class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Specialization</flux:label>
                    <flux:input wire:model="specializations" placeholder="Cardiology, General…" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Experience (years)</flux:label>
                    <flux:input wire:model="experience_years" type="number" min="0" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Joining Date *</flux:label>
                    <flux:input wire:model="joining_date" type="date" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">License Number</flux:label>
                    <flux:input wire:model="license_number" class="mt-1 w-full rounded-xl" />
                </div>
                <div>
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">License Expiry</flux:label>
                    <flux:input wire:model="license_expiry" type="date" class="mt-1 w-full rounded-xl" />
                </div>
                @if ($role === 'doctor')
                    <div>
                        <flux:label class="text-sm text-slate-600 dark:text-slate-300">Consultation Fee (₹)</flux:label>
                        <flux:input wire:model="consultation_fee" type="number" min="0" class="mt-1 w-full rounded-xl" />
                    </div>
                    <div class="md:col-span-1 flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                        <input type="checkbox" wire:model="is_available_for_online" class="w-4 h-4 rounded text-indigo-600" id="online-available" />
                        <label for="online-available" class="text-sm text-slate-700 dark:text-slate-300">Available for online consultations</label>
                    </div>
                @endif
                <div class="md:col-span-2">
                    <flux:label class="text-sm text-slate-600 dark:text-slate-300">Biography</flux:label>
                    <flux:textarea wire:model="biography" rows="3" placeholder="Brief professional bio…" class="mt-1 w-full rounded-xl" />
                </div>
            </div>
        </div>

        {{-- Doctor Schedule --}}
        @if ($role === 'doctor')
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="font-semibold text-slate-900 dark:text-white">Weekly Schedule</h2>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model.live="addSchedule" class="w-4 h-4 rounded text-indigo-600" id="add-schedule" />
                        <label for="add-schedule" class="text-sm text-slate-600 dark:text-slate-300">Set schedule now</label>
                    </div>
                </div>

                @if ($addSchedule)
                    <div class="space-y-3">
                        @foreach ($schedules as $i => $schedule)
                            <div class="grid grid-cols-12 items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800">
                                <div class="col-span-2 flex items-center gap-2">
                                    <input type="checkbox" wire:model.live="schedules.{{ $i }}.is_available"
                                           class="w-4 h-4 rounded text-indigo-600" />
                                    <span class="text-sm font-medium capitalize text-slate-700 dark:text-slate-300">
                                        {{ substr($schedule['day'], 0, 3) }}
                                    </span>
                                </div>
                                @if ($schedules[$i]['is_available'])
                                    <div class="col-span-3">
                                        <input type="time" wire:model="schedules.{{ $i }}.start_time"
                                               class="w-full px-2 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-white" />
                                    </div>
                                    <span class="text-slate-400 text-sm text-center">to</span>
                                    <div class="col-span-3">
                                        <input type="time" wire:model="schedules.{{ $i }}.end_time"
                                               class="w-full px-2 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-white" />
                                    </div>
                                    <div class="col-span-2">
                                        <input type="number" wire:model="schedules.{{ $i }}.max_patients"
                                               min="1" max="100" placeholder="Max pts"
                                               class="w-full px-2 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-sm text-slate-900 dark:text-white" />
                                    </div>
                                @else
                                    <div class="col-span-10 text-sm text-slate-400 italic">Day off</div>
                                @endif
                            </div>
                        @endforeach
                        <p class="text-xs text-slate-500">💡 Slot duration: 30 min · Buffer: 5 min</p>
                    </div>
                @endif
            </div>
        @endif

        {{-- Submit --}}
        <div class="flex gap-3 pb-4">
            <a href="{{ route('admin.staff.index', $clinic->id) }}" wire:navigate class="flex-1">
                <flux:button variant="ghost" class="w-full border-slate-200 dark:border-slate-700 rounded-xl">Cancel</flux:button>
            </a>
            <flux:button wire:click="save" wire:loading.attr="disabled"
                         class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white border-0 rounded-xl font-semibold">
                <span wire:loading.remove>Add Staff Member</span>
                <span wire:loading>Adding…</span>
            </flux:button>
        </div>

    </div>
</div>
