<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class MexicoSeeder extends Seeder
{
    public function run(): void
    {
        // Leer archivos JSON desde /database/data/
        $countries = collect(json_decode(File::get(database_path('data/countries.json'))));
        $states = collect(json_decode(File::get(database_path('data/states.json'))));
        $cities = collect(json_decode(File::get(database_path('data/cities.json'))));

        // Buscar el país México
        $mexico = $countries->firstWhere('iso2', 'MX');

        if (!$mexico) {
            $this->command->error('México no fue encontrado en countries.json');
            return;
        }

        // Insertar país
        Country::updateOrCreate(
            ['id' => $mexico->id],
            [
                'name' => $mexico->name,
                'iso2' => $mexico->iso2,
                'iso3' => $mexico->iso3,
                'phonecode' => $mexico->phonecode,
                'capital' => $mexico->capital,
                'currency' => $mexico->currency,
                'native' => $mexico->native,
                'emoji' => $mexico->emoji,
                'emojiU' => $mexico->emojiU,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Insertar estados de México
        $mxStates = $states->where('country_id', $mexico->id);
        foreach ($mxStates as $state) {
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

        // Insertar ciudades de México
        $mxCities = $cities->where('country_id', $mexico->id);
        foreach ($mxCities as $city) {
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

        $this->command->info('México importado: ' . $mxStates->count() . ' estados y ' . $mxCities->count() . ' ciudades.');
    }
}
