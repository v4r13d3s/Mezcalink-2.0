<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class WorldSeeder extends Seeder
{
    public function run(): void
    {
        // Leer archivos JSON
        $countries = collect(json_decode(File::get(database_path('data/countries.json'))));
        $states = collect(json_decode(File::get(database_path('data/states.json'))));
        $cities = collect(json_decode(File::get(database_path('data/cities.json'))));

        $this->command->info("Cargando países...");

        foreach ($countries as $country) {
            Country::updateOrCreate(
                ['id' => $country->id],
                [
                    'name' => $country->name,
                    'iso2' => $country->iso2,
                    'iso3' => $country->iso3,
                    'phonecode' => $country->phonecode,
                    'capital' => $country->capital,
                    'currency' => $country->currency,
                    'native' => $country->native,
                    'emoji' => $country->emoji,
                    'emojiU' => $country->emojiU,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info("Cargando estados...");

        foreach ($states as $state) {
            State::updateOrCreate(
                ['id' => $state->id],
                [
                    'country_id' => $state->country_id,
                    'name' => $state->name,
                    'state_code' => $state->state_code,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info("Cargando ciudades...");

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['id' => $city->id],
                [
                    'state_id' => $city->state_id,
                    'country_id' => $city->country_id,
                    'name' => $city->name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info("✅ Seeder completado: {$countries->count()} países, {$states->count()} estados y {$cities->count()} ciudades.");
    }
}
