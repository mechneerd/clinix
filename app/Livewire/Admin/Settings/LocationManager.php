<?php

namespace App\Livewire\Admin\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Country;
use App\Models\Region;
use App\Models\Subregion;
use App\Models\City;
use App\Models\Area;

class LocationManager extends Component
{
    use WithPagination;

    public $activeTab = 'regions'; // regions, subregions, cities, areas
    
    // Filters
    public $search = '';
    public $selectedCountryId = null;
    public $selectedRegionId = null;
    public $selectedSubregionId = null;
    public $selectedCityId = null;

    // Form fields
    public $isEditing = false;
    public $editingId = null;
    public $formData = [];

    // Modal
    public $showModal = false;

    protected $queryString = ['activeTab', 'selectedCountryId'];

    public function updatedActiveTab()
    {
        $this->resetPage();
        $this->resetFilters();
    }

    public function resetFilters()
    {
        $this->selectedRegionId = null;
        $this->selectedSubregionId = null;
        $this->selectedCityId = null;
        $this->search = '';
    }

    public function openModal($id = null)
    {
        $this->resetValidation();
        $this->isEditing = $id ? true : false;
        $this->editingId = $id;
        
        if ($this->isEditing) {
            $modelClass = $this->getModelClass();
            $item = $modelClass::findOrFail($id);
            $this->formData = $item->toArray();
        } else {
            $this->formData = [
                'country_id' => $this->selectedCountryId,
                'region_id' => $this->selectedRegionId,
                'subregion_id' => $this->selectedSubregionId,
                'city_id' => $this->selectedCityId,
                'name' => '',
                'is_active' => true,
            ];
        }

        $this->showModal = true;
    }

    public function save()
    {
        $rules = [
            'formData.name' => 'required|string|max:255',
            'formData.is_active' => 'boolean',
        ];

        if ($this->activeTab === 'regions') {
            $rules['formData.country_id'] = 'required|exists:countries,id';
        } elseif ($this->activeTab === 'subregions') {
            $rules['formData.region_id'] = 'required|exists:regions,id';
        } elseif ($this->activeTab === 'cities') {
            $rules['formData.region_id'] = 'required|exists:regions,id';
            $rules['formData.subregion_id'] = 'nullable|exists:subregions,id';
        } elseif ($this->activeTab === 'areas') {
            $rules['formData.city_id'] = 'required|exists:cities,id';
        }

        $this->validate($rules);

        $modelClass = $this->getModelClass();
        
        if ($this->isEditing) {
            $item = $modelClass::findOrFail($this->editingId);
            $item->update($this->formData);
            session()->flash('success', ucfirst($this->activeTab) . ' updated successfully.');
        } else {
            $modelClass::create($this->formData);
            session()->flash('success', ucfirst($this->activeTab) . ' created successfully.');
        }

        $this->showModal = false;
    }

    public function delete($id)
    {
        $modelClass = $this->getModelClass();
        $item = $modelClass::findOrFail($id);
        
        // Check for children
        $hasChildren = false;
        if ($this->activeTab === 'regions') {
            $hasChildren = $item->subregions()->exists() || $item->cities()->exists();
        } elseif ($this->activeTab === 'subregions') {
            $hasChildren = $item->cities()->exists();
        } elseif ($this->activeTab === 'cities') {
            $hasChildren = $item->areas()->exists();
        }

        if ($hasChildren) {
            session()->flash('error', 'Cannot delete this ' . $this->activeTab . ' because it has associated child locations.');
            return;
        }

        $item->delete();
        session()->flash('success', ucfirst($this->activeTab) . ' deleted successfully.');
    }

    private function getModelClass()
    {
        return match($this->activeTab) {
            'regions' => Region::class,
            'subregions' => Subregion::class,
            'cities' => City::class,
            'areas' => Area::class,
        };
    }

    public function render()
    {
        $countries = Country::orderBy('name')->get();
        $regions = $this->selectedCountryId ? Region::where('country_id', $this->selectedCountryId)->orderBy('name')->get() : collect();
        $subregions = $this->selectedRegionId ? Subregion::where('region_id', $this->selectedRegionId)->orderBy('name')->get() : collect();
        $cities = $this->selectedSubregionId ? City::where('subregion_id', $this->selectedSubregionId)->orderBy('name')->get() : ($this->selectedRegionId ? City::where('region_id', $this->selectedRegionId)->orderBy('name')->get() : collect());

        $items = collect();
        if ($this->activeTab === 'regions') {
            $query = Region::query()->with('country');
            if ($this->selectedCountryId) $query->where('country_id', $this->selectedCountryId);
            if ($this->search) $query->where('name', 'like', '%' . $this->search . '%');
            $items = $query->orderBy('name')->paginate(10);
        } elseif ($this->activeTab === 'subregions') {
            $query = Subregion::query()->with('region.country');
            if ($this->selectedRegionId) $query->where('region_id', $this->selectedRegionId);
            elseif ($this->selectedCountryId) $query->whereHas('region', fn($q) => $q->where('country_id', $this->selectedCountryId));
            if ($this->search) $query->where('name', 'like', '%' . $this->search . '%');
            $items = $query->orderBy('name')->paginate(10);
        } elseif ($this->activeTab === 'cities') {
            $query = City::query()->with(['region', 'subregion']);
            if ($this->selectedSubregionId) $query->where('subregion_id', $this->selectedSubregionId);
            elseif ($this->selectedRegionId) $query->where('region_id', $this->selectedRegionId);
            elseif ($this->selectedCountryId) $query->whereHas('region', fn($q) => $q->where('country_id', $this->selectedCountryId));
            if ($this->search) $query->where('name', 'like', '%' . $this->search . '%');
            $items = $query->orderBy('name')->paginate(10);
        } elseif ($this->activeTab === 'areas') {
            $query = Area::query()->with('city.region');
            if ($this->selectedCityId) $query->where('city_id', $this->selectedCityId);
            elseif ($this->selectedSubregionId) $query->whereHas('city', fn($q) => $q->where('subregion_id', $this->selectedSubregionId));
            elseif ($this->selectedRegionId) $query->whereHas('city', fn($q) => $q->where('region_id', $this->selectedRegionId));
            if ($this->search) $query->where('name', 'like', '%' . $this->search . '%');
            $items = $query->orderBy('name')->paginate(10);
        }

        return view('livewire.admin.settings.location-manager', [
            'items' => $items,
            'countries' => $countries,
            'regions' => $regions,
            'subregions' => $subregions,
            'cities' => $cities,
        ]);
    }
}
