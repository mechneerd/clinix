<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <!-- Region -->
    <div class="space-y-2">
        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Region / State / Emirate</label>
        <select wire:model.live="region_id" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all text-sm font-medium dark:text-white">
            <option value="">Select Region</option>
            @foreach($regions as $region)
                <option value="{{ $region->id }}">{{ $region->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Subregion -->
    @if(count($subregions) > 0)
    <div class="space-y-2">
        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Subregion / District / County</label>
        <select wire:model.live="subregion_id" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all text-sm font-medium dark:text-white">
            <option value="">Select Subregion</option>
            @foreach($subregions as $sub)
                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
            @endforeach
        </select>
    </div>
    @endif

    <!-- City -->
    <div class="space-y-2">
        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">City / Town</label>
        <select wire:model.live="city_id" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all text-sm font-medium dark:text-white">
            <option value="">Select City</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}">{{ $city->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Area -->
    @if(count($areas) > 0)
    <div class="space-y-2">
        <label class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Area / Ward / Village</label>
        <select wire:model.live="area_id" class="w-full h-12 bg-slate-50 dark:bg-slate-800 border-none rounded-2xl px-4 focus:ring-2 focus:ring-brand-teal transition-all text-sm font-medium dark:text-white">
            <option value="">Select Area</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}">{{ $area->name }}</option>
            @endforeach
        </select>
    </div>
    @endif
</div>
