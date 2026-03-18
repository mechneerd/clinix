<div class="space-y-8">
    <!-- Header -->
    <div class="relative overflow-hidden rounded-[2.5rem] bg-slate-900 border border-slate-800 p-8 shadow-2xl">
        <div class="absolute top-0 right-0 -mt-20 -mr-20 w-80 h-80 bg-blue-500/10 rounded-full blur-3xl"></div>
        <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-500 mb-3">
                    <span class="text-blue-500">Service Discovery</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"></path></svg>
                    <span>Browse Clinics</span>
                </nav>
                <h1 class="text-4xl font-black text-white tracking-tight">Find Your <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Care Center</span></h1>
                <p class="text-slate-400 mt-2 font-medium">Browse through our network of premium clinics and specialized centers.</p>
            </div>
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative group">
                    <x-icons name="search" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-blue-500 transition-colors" />
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search clinics..." class="pl-12 pr-6 py-3 bg-slate-800 border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all w-full md:w-64">
                </div>
                <div class="relative group">
                    <x-icons name="location" class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-blue-500 transition-colors" />
                    <input type="text" wire:model.live.debounce.300ms="city" placeholder="City..." class="pl-12 pr-6 py-3 bg-slate-800 border-slate-700 text-white rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all w-full md:w-48">
                </div>
            </div>
        </div>
    </div>

    <!-- Clinics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse ($clinics as $clinic)
            <div class="bg-slate-900 border border-slate-800 rounded-[2.5rem] p-8 hover:border-blue-500/30 transition-all group flex flex-col h-full shadow-xl overflow-hidden relative">
                <div class="absolute top-0 right-0 p-6">
                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-500/20">Active</span>
                </div>
                
                <div class="flex items-center gap-6 mb-8 mt-2">
                    <div class="w-20 h-20 rounded-3xl bg-slate-800 border border-slate-700 p-3 flex items-center justify-center overflow-hidden group-hover:scale-105 transition-transform shadow-inner">
                        @if($clinic->logo)
                            <img src="{{ asset('storage/' . $clinic->logo) }}" alt="{{ $clinic->name }}" class="w-full h-full object-cover">
                        @else
                            <x-icons name="hospital" class="w-10 h-10 text-slate-600" />
                        @endif
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white group-hover:text-blue-400 transition-colors line-clamp-1">{{ $clinic->name }}</h3>
                        <p class="text-slate-500 text-sm font-medium mt-1 flex items-center gap-1">
                            <x-icons name="location" class="w-4 h-4" />
                            {{ $clinic->city }}, {{ $clinic->country }}
                        </p>
                    </div>
                </div>

                <div class="space-y-4 mb-8 flex-grow">
                    <div class="p-4 bg-slate-800/50 rounded-2xl border border-slate-800 space-y-2">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Contact Details</p>
                        <div class="text-slate-300 text-xs flex items-center gap-2">
                            <x-icons name="mail" class="w-3 h-3 text-blue-500" />
                            {{ $clinic->email }}
                        </div>
                        <div class="text-slate-300 text-xs flex items-center gap-2">
                            <x-icons name="phone" class="w-3 h-3 text-blue-500" />
                            {{ $clinic->phone }}
                        </div>
                    </div>
                    
                    <p class="text-slate-400 text-xs line-clamp-3 leading-relaxed italic">
                        {{ $clinic->description ?: 'Premium clinical services provided with care and excellence.' }}
                    </p>
                </div>

                <div class="pt-6 border-t border-slate-800">
                    <a href="{{ route('patient.book-appointment', ['clinic_slug' => $clinic->slug]) }}" wire:navigate class="w-full inline-flex items-center justify-center px-6 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl font-bold transition-all shadow-lg active:scale-95 group/btn">
                        <span>Book Appointment</span>
                        <x-icons name="arrow-right" class="w-5 h-5 ml-2 group-hover/btn:translate-x-1 transition-transform" />
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="w-24 h-24 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-6 border border-slate-700 shadow-inner">
                    <x-icons name="search" class="w-12 h-12 text-slate-600" />
                </div>
                <h3 class="text-white font-bold text-xl mb-2">No clinics found</h3>
                <p class="text-slate-500 max-w-sm mx-auto">Try adjusting your search filters or browse by city to find available care centers.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-12">
        {{ $clinics->links() }}
    </div>
</div>
