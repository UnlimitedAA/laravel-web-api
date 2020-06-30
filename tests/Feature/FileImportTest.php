<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\City;
use App\Attraction;

class FileImportTest extends TestCase
{
    use RefreshDatabase;


    public function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function test_file_import_command()
    {
        $this->artisan('file:import')
         ->expectsQuestion('Where is the file located at?', realpath(__DIR__ . '/../Data/Disks/Local/attraction-import.csv'))
         ->expectsQuestion('What is the table name to save the data to?', 'cities')
         ->expectsQuestion('What is the table name to save the data to?', 'attractions')
         ->expectsOutput('Data added successfully')
         ->assertExitCode(1);

        $this->assertCount(5, Attraction::all());
        $this->assertCount(3, City::all());
    }
}
