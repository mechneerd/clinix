<div class="p-6 lg:p-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.clinics.show', $clinic->id) }}" wire:navigate class="w-9 h-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center hover:bg-slate-200 transition-colors">
                <flux:icon name="arrow-left" class="w-4 h-4 text-slate-600 dark:text-slate-300" />
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Staff & Doctors</h1>
                <p class="text-slate-500 text-sm">{{ $clinic->name }}</p>
            </div>
        </div>
        <a href="{{ route('admin.staff.add', $clinic->id) }}" wire:navigate>
            <flux:button class="bg-indigo-600 text-white border-0 rounded-xl" icon="plus">Add Staff</flux:button>
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 sm:grid-cols-5 gap-3">
        @foreach ([
            ['label'=>'Total',    'value'=>$stats['total'],          'color'=>'slate'],
            ['label'=>'Doctors',  'value'=>$stats['doctors'],        'color'=>'indigo'],
            ['label'=>'Nurses',   'value'=>$stats['nurses'],         'color'=>'emerald'],
            ['label'=>'Lab',      'value'=>$stats['lab_staff'],      'color'=>'violet'],
            ['label'=>'Pharmacy', 'value'=>$stats['pharmacy_staff'], 'color'=>'amber'],
        ] as $s)
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-4 text-center cursor-pointer"
                 wire:click="$set('roleFilter', '{{ $s['label'] === 'Total' ? '' : strtolower($s['label']) }}')">
                <div class="text-2xl font-bold text-{{ $s['color'] }}-600 dark:text-{{ $s['color'] }}-400">{{ $s['value'] }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $s['label'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-48">
            <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by name or email…" icon="magnifying-glass" class="w-full rounded-xl" />
        </div>
        <flux:select wire:model.live="roleFilter" class="rounded-xl w-44">
            <option value="">All Roles</option>
            @foreach (['doctor','nurse','lab_technician','pharmacist','manager','receptionist'] as $role)
                <option value="{{ $role }}">{{ ucwords(str_replace('_',' ',$role)) }}</option>
            @endforeach
        </flux:select>
    </div>

    {{-- Staff Table --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="border-b border-slate-100 dark:border-slate-800">
                <tr>
                    @foreach (['Member','Role','Department','Employment','Joined','Actions'] as $h)
                        <th class="text-left px-5 py-3 text-xs font-medium text-slate-500 uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse ($staff as $profile)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-sm font-bold text-indigo-600 flex-shrink-0">
                                    {{ strtoupper(substr($profile->user->name ?? 'S', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-slate-900 dark:text-white">{{ $profile->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-slate-500">{{ $profile->user->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            @foreach ($profile->user->roles ?? [] as $role)
                                <span @class(['text-xs px-2.5 py-0.5 rounded-full font-medium',
                                              'bg-indigo-100 text-indigo-700' => $role->name === 'doctor',
                                              'bg-emerald-100 text-emerald-700' => $role->name === 'nurse',
                                              'bg-violet-100 text-violet-700' => $role->name === 'lab_technician',
                                              'bg-amber-100 text-amber-700' => $role->name === 'pharmacist',
                                              'bg-slate-100 text-slate-700' => !in_array($role->name,['doctor','nurse','lab_technician','pharmacist'])])>
                                    {{ ucwords(str_replace('_',' ',$role->name)) }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-5 py-3 text-sm text-slate-600 dark:text-slate-400">{{ $profile->department->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="text-xs bg-slate-100 dark:bg-slate-800 text-slate-600 px-2 py-0.5 rounded-full capitalize">
                                {{ str_replace('_',' ',$profile->employment_type) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ $profile->joining_date?->format('M d, Y') ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <div class="flex gap-1">
                                <flux:button size="xs" variant="ghost" icon="pencil" class="border-slate-200 dark:border-slate-700 rounded-lg" />
                                <button wire:click="confirmRemove({{ $profile->id }})"
                                        class="p-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 text-red-400 hover:text-red-600 transition-colors">
                                    <flux:icon name="x-mark" class="w-4 h-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center text-slate-500 text-sm">
                            No staff found. <a href="{{ route('admin.staff.add', $clinic->id) }}" wire:navigate class="text-indigo-600 underline">Add your first staff member</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t border-slate-100 dark:border-slate-800">{{ $staff->links() }}</div>
    </div>

    {{-- Remove Modal --}}
    @if ($showRemoveModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-white dark:bg-slate-900 rounded-3xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-amber-50 flex items-center justify-center">
                    <flux:icon name="user-minus" class="w-7 h-7 text-amber-500" />
                </div>
                <h3 class="font-bold text-slate-900 dark:text-white mb-2">Remove Staff Member?</h3>
                <p class="text-slate-500 text-sm mb-6">This will mark their leaving date as today. Their records will be preserved.</p>
                <div class="flex gap-3">
                    <flux:button wire:click="$set('showRemoveModal',false)" variant="ghost" class="flex-1">Cancel</flux:button>
                    <flux:button wire:click="removeStaff" class="flex-1 bg-amber-500 text-white border-0">Remove</flux:button>
                </div>
            </div>
        </div>
    @endif

</div>
