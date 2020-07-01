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
        factory(App\City::class, 500)
            ->create()
            ->each(function ($city) {
                $city->attractions()->createMany(
                    factory(App\Attraction::class, rand(1, 10))->make()->toArray()
                );
            });
    }
}
