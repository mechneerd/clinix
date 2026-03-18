<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\Region;
use App\Models\Subregion;
use App\Models\City;
use App\Models\Area;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. India
        $india = Country::where('iso_code', 'IN')->first();
        if ($india) {
            $kerala = Region::create(['country_id' => $india->id, 'name' => 'Kerala']);
            $tvm = Subregion::create(['region_id' => $kerala->id, 'name' => 'Thiruvananthapuram']);
            $neyyattinkara = City::create(['region_id' => $kerala->id, 'subregion_id' => $tvm->id, 'name' => 'Neyyattinkara']);
            Area::create(['city_id' => $neyyattinkara->id, 'name' => 'Amaravila']);
        }

        // 2. USA
        $usa = Country::where('iso_code', 'US')->first();
        if ($usa) {
            $california = Region::create(['country_id' => $usa->id, 'name' => 'California']);
            $la_county = Subregion::create(['region_id' => $california->id, 'name' => 'Los Angeles County']);
            $la_city = City::create(['region_id' => $california->id, 'subregion_id' => $la_county->id, 'name' => 'Los Angeles']);
            Area::create(['city_id' => $la_city->id, 'name' => 'Hollywood']);
        }

        // 3. UAE
        $uae = Country::where('iso_code', 'AE')->first();
        if ($uae) {
            $dubai_emirate = Region::create(['country_id' => $uae->id, 'name' => 'Dubai']);
            $dubai_city = City::create(['region_id' => $dubai_emirate->id, 'name' => 'Dubai']);
            Area::create(['city_id' => $dubai_city->id, 'name' => 'Downtown Dubai']);
        }
    }
}
