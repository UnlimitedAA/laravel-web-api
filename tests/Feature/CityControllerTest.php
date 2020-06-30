<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\City;
use App\Attraction;

class CityControllerTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function test_city_can_be_listed()
    {
        $cities = factory(City::class, 3)->create();

        $response = $this->json('GET', '/api/cities');

        $response->assertStatus(200);
        $this->assertCount(3, $response->original);
    }

    public function test_cities_can_be_deleted()
    {
        $city = factory(City::class)->create();
        $city->attractions()->save(factory(Attraction::class)->make());

        $response = $this->json('DELETE', '/api/cities/' . $city->id);

        $response->assertStatus(204);

        $this->assertDeleted($city);
        $this->assertCount(0, Attraction::all());

    }

    public function test_city_can_be_edited()
    {
        $city = factory(City::class)->create(['name' => 'Kuala Lumpur']);

        $response = $this->json('PUT', '/api/cities/' . $city->id, [
            'name' => 'Melbourne',
        ]);

        $response->assertStatus(200);
        $response->assertSee('Melbourne', $escaped = false);
        $response->assertDontSee('Kuala Lumpur', $escaped = false);
    }
}
