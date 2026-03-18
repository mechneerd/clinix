<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Manage Clinics</h1>
        <input wire:model.live="search" type="text" placeholder="Search clinics..." class="px-4 py-2 bg-slate-800 border border-slate-700 rounded-lg text-white">
    </div>
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-700/50">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-300">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-300">Admin</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-300">Package</th>
                    <th class="px-4 py-3 text-left text-sm font-medium text-slate-300">Status</th>
                    <th class="px-4 py-3 text-right text-sm font-medium text-slate-300">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-700">
                @foreach($clinics as $clinic)
                <tr class="hover:bg-slate-700/30">
                    <td class="px-4 py-3 text-white">{{ $clinic->name }}</td>
                    <td class="px-4 py-3 text-slate-400">{{ $clinic->admin->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3 text-slate-400">{{ $clinic->package->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-lg text-xs {{ $clinic->isActive() ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}">
                            {{ $clinic->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if($clinic->user_id)
                        <a href="{{ route('super-admin.admin.modules', $clinic->user_id) }}" 
                           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-brand-teal/10 text-brand-teal hover:bg-brand-teal hover:text-white transition-all duration-200 text-xs font-bold"
                           wire:navigate>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                            Manage Modules
                        </a>
                        @else
                        <span class="text-xs text-slate-500 italic">No Admin</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    {{ $clinics->links() }}
</div>