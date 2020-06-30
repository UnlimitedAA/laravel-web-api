<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\City;
use App\Attraction;

class AttractionControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function test_attraction_can_be_listed()
    {
        $city = factory(City::class)->create();
        $city->attractions()->save(factory(Attraction::class)->make());
        $attraction = factory(Attraction::class, 3)->create();

        $response = $this->json('GET', '/api/attractions');

        $response->assertStatus(200);
        $this->assertCount(4, $response->original);
    }

    public function test_attraction_can_be_deleted()
    {
        $city = factory(City::class)->create();
        $city->attractions()->save(factory(Attraction::class)->make());
        $attraction = $city->attractions->first();

        $response = $this->json('DELETE', '/api/attractions/' . $attraction->id);

        $response->assertStatus(204);
        $this->assertDeleted($attraction);
    }

    public function test_attraction_can_be_edited()
    {
        $city = factory(City::class)->create();
        $city->attractions()->save(
            factory(Attraction::class)->make([
                'name' => 'Twin Towers',
            ])
        );
        $attraction = $city->attractions->first();

        $response = $this->json('PUT', '/api/attractions/' . $attraction->id, [
            'name' => 'Lake',
            'city_id' => $city->id
        ]);

        $response->assertStatus(200);
        $response->assertSee('Lake', $escaped = false);
        $response->assertDontSee('Twin Towers', $escaped = false);
    }
}
