<div class="p-6 space-y-8 bg-slate-50 dark:bg-slate-950 min-h-screen">
    <!-- Header Area -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl p-8 border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2">
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-brand-teal/10 flex items-center justify-center">
                        <x-icons name="map-pin" class="w-6 h-6 text-brand-teal" />
                    </div>
                    Location Management
                </h1>
                <p class="text-slate-500 dark:text-slate-400 pl-15">Configure Regions, Cities, and granular Area records.</p>
            </div>
            <button wire:click="openModal" class="flex items-center gap-2 bg-brand-teal hover:bg-brand-teal-dark text-white px-6 py-3 rounded-2xl font-semibold transition-all hover:scale-105 active:scale-95 shadow-lg shadow-brand-teal/20">
                <x-icons name="plus" class="w-5 h-5" />
                Add New {{ ucfirst(Str::singular($activeTab)) }}
            </button>
        </div>
    </div>

    <!-- Tabs Area -->
    <div class="flex gap-2 p-1 bg-slate-200/50 dark:bg-slate-800/50 rounded-2xl w-fit">
        @foreach(['regions', 'subregions', 'cities', 'areas'] as $tab)
            <button 
                wire:click="$set('activeTab', '{{ $tab }}')"
                class="px-6 py-2.5 rounded-xl font-medium transition-all {{ $activeTab === $tab ? 'bg-white dark:bg-slate-700 text-brand-teal shadow-sm' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                {{ ucfirst($tab) }}
            </button>
        @endforeach
    </div>

    <!-- Filters Area -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="relative group">
            <x-icons name="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 group-focus-within:text-brand-teal transition-colors" />
            <input type="text" wire:model.live="search" placeholder="Search by name..." 
                   class="w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-2xl pl-12 pr-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-teal/20 focus:border-brand-teal transition-all outline-none">
        </div>

        <div>
            <select wire:model.live="selectedCountryId" class="w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-teal/20 outline-none">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->flag }} {{ $country->name }}</option>
                @endforeach
            </select>
        </div>

        @if($activeTab !== 'regions')
            <div>
                <select wire:model.live="selectedRegionId" class="w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-teal/20 outline-none">
                    <option value="">All Regions</option>
                    @foreach($regions as $region)
                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if($activeTab === 'areas')
            <div>
                <select wire:model.live="selectedCityId" class="w-full bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 rounded-2xl px-4 py-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-brand-teal/20 outline-none">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>

    <!-- Table Area -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-none">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Name</th>
                        @if($activeTab === 'regions')
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Country</th>
                        @elseif($activeTab === 'subregions')
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Region</th>
                        @elseif($activeTab === 'cities')
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Region / Subregion</th>
                        @elseif($activeTab === 'areas')
                            <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">City</th>
                        @endif
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300">Status</th>
                        <th class="px-6 py-4 text-sm font-semibold text-slate-600 dark:text-slate-300 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($items as $item)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-medium text-slate-900 dark:text-white">{{ $item->name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($activeTab === 'regions')
                                    <span class="text-sm text-slate-500">{{ $item->country->name }}</span>
                                @elseif($activeTab === 'subregions')
                                    <span class="text-sm text-slate-500">{{ $item->region->name }}</span>
                                @elseif($activeTab === 'cities')
                                    <span class="text-sm text-slate-500">{{ $item->region->name }} {{ $item->subregion ? '/ ' . $item->subregion->name : '' }}</span>
                                @elseif($activeTab === 'areas')
                                    <span class="text-sm text-slate-500">{{ $item->city->name }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $item->is_active ? 'bg-emerald-500/10 text-emerald-500' : 'bg-rose-500/10 text-rose-500' }}">
                                    {{ $item->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="openModal({{ $item->id }})" class="p-2 text-slate-400 hover:text-brand-teal transition-colors">
                                        <x-icons name="settings" class="w-5 h-5" />
                                    </button>
                                    <button onclick="confirm('Are you sure?') || event.stopImmediatePropagation()" wire:click="delete({{ $item->id }})" class="p-2 text-slate-400 hover:text-rose-500 transition-colors">
                                        <x-icons name="trash" class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                        <x-icons name="map-pin" class="w-8 h-8 text-slate-300" />
                                    </div>
                                    <p class="text-slate-500 dark:text-slate-400">No {{ $activeTab }} found matching your filters.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($items->hasPages())
            <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800">
                {{ $items->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Area -->
    @if($showModal)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-opacity">
            <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] w-full max-w-lg overflow-hidden flex flex-col shadow-2xl animate-in zoom-in-95 duration-200">
                <div class="px-8 py-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $isEditing ? 'Edit' : 'Add New' }} {{ ucfirst(Str::singular($activeTab)) }}</h2>
                    <button wire:click="$set('showModal', false)" class="text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                        <x-icons name="plus" class="w-6 h-6 rotate-45" />
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    @if($activeTab === 'regions')
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Country</label>
                            <select wire:model="formData.country_id" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-brand-teal/20 transition-all">
                                <option value="">Select Country</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->flag }} {{ $country->name }}</option>
                                @endforeach
                            </select>
                            @error('formData.country_id') <span class="text-xs text-rose-500 ml-1">{{ $message }}</span> @enderror
                        </div>
                    @endif

                    @if($activeTab === 'subregions' || $activeTab === 'cities')
                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Country</label>
                                <select wire:model.live="selectedCountryId" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none">
                                    <option value="">Select Country</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->flag }} {{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Region</label>
                                <select wire:model="formData.region_id" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-brand-teal/20 transition-all">
                                    <option value="">Select Region</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                                @error('formData.region_id') <span class="text-xs text-rose-500 ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    @if($activeTab === 'areas')
                        <div class="space-y-4">
                           <!-- Complex dependency for areas can be added here or just city dropdown if filtered -->
                           <div class="space-y-2">
                                <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">City</label>
                                <select wire:model="formData.city_id" class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-brand-teal/20 transition-all">
                                    <option value="">Select City</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                                @error('formData.city_id') <span class="text-xs text-rose-500 ml-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endif

                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700 dark:text-slate-300 ml-1">Name</label>
                        <input type="text" wire:model="formData.name" placeholder="Enter name..." 
                               class="w-full bg-slate-50 dark:bg-slate-800 border-slate-200 dark:border-slate-700 rounded-2xl px-4 py-3 outline-none focus:ring-2 focus:ring-brand-teal/20 transition-all">
                        @error('formData.name') <span class="text-xs text-rose-500 ml-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-2xl">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model="formData.is_active" class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-teal"></div>
                            <span class="ml-3 text-sm font-medium text-slate-700 dark:text-slate-300">Active Status</span>
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 flex gap-3">
                    <button wire:click="$set('showModal', false)" class="flex-1 px-6 py-3 rounded-2xl font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all">Cancel</button>
                    <button wire:click="save" class="flex-1 bg-brand-teal hover:bg-brand-teal-dark text-white px-6 py-3 rounded-2xl font-semibold transition-all shadow-lg shadow-brand-teal/20">
                        {{ $isEditing ? 'Save Changes' : 'Create ' . ucfirst(Str::singular($activeTab)) }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
