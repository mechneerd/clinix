<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\Region;
use App\Models\Subregion;
use App\Models\City;
use App\Models\Area;
use App\Models\Country;

class AddressSelector extends Component
{
    public $country_id;
    public $region_id;
    public $subregion_id;
    public $city_id;
    public $area_id;

    public $regions = [];
    public $subregions = [];
    public $cities = [];
    public $areas = [];

    protected $listeners = ['country-selected' => 'updatedCountryId'];

    public function mount($country_id = null, $region_id = null, $subregion_id = null, $city_id = null, $area_id = null)
    {
        $this->country_id = $country_id;
        $this->region_id = $region_id;
        $this->subregion_id = $subregion_id;
        $this->city_id = $city_id;
        $this->area_id = $area_id;

        if ($this->country_id) {
            $this->loadRegions();
        }
        if ($this->region_id) {
            $this->loadSubregions();
            $this->loadCities();
        }
        if ($this->subregion_id) {
            $this->loadCities();
        }
        if ($this->city_id) {
            $this->loadAreas();
        }
    }

    public function updatedCountryId($id)
    {
        $this->country_id = $id;
        $this->region_id = null;
        $this->subregion_id = null;
        $this->city_id = null;
        $this->area_id = null;
        $this->loadRegions();
        $this->dispatch('location-updated', 'country', $id);
    }

    public function updatedRegionId($id)
    {
        $this->subregion_id = null;
        $this->city_id = null;
        $this->area_id = null;
        $this->loadSubregions();
        $this->loadCities();
        $this->dispatch('location-updated', 'region', $id);
    }

    public function updatedSubregionId($id)
    {
        $this->city_id = null;
        $this->area_id = null;
        $this->loadCities();
        $this->dispatch('location-updated', 'subregion', $id);
    }

    public function updatedCityId($id)
    {
        $this->area_id = null;
        $this->loadAreas();
        $this->dispatch('location-updated', 'city', $id);
    }

    public function updatedAreaId($id)
    {
        $this->dispatch('location-updated', 'area', $id);
    }

    private function loadRegions()
    {
        $this->regions = Region::where('country_id', $this->country_id)->get();
        $this->subregions = [];
        $this->cities = [];
        $this->areas = [];
    }

    private function loadSubregions()
    {
        $this->subregions = Subregion::where('region_id', $this->region_id)->get();
    }

    private function loadCities()
    {
        $query = City::where('region_id', $this->region_id);
        if ($this->subregion_id) {
            $query->where('subregion_id', $this->subregion_id);
        }
        $this->cities = $query->get();
    }

    private function loadAreas()
    {
        $this->areas = Area::where('city_id', $this->city_id)->get();
    }

    public function render()
    {
        return view('livewire.components.address-selector');
    }
}
