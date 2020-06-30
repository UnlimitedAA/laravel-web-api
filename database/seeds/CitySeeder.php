<?php

use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\City::class, 50)
            ->create()
            ->each(function ($city) {
                $city->posts()->save(factory(App\Attraction::class)->make());
            });
    }
}
