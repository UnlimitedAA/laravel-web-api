<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\City;

class ImportFile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import file from local disk and save the data into the database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $target = $this->ask('Where is the file located at?');
        $cityTable = $this->ask('What is the table name to save the data to?');
        $attractionTable = $this->ask('What is the table name to save the data to?');

        // $target = $this->argument('target');
        // $cityTable = $this->argument('cityTable');
        // $attractionTable = $this->argument('attractionTable');

        $CSVFile = $target;

        if(!file_exists($CSVFile) || !is_readable($CSVFile)) {
            $this->error('File not found');
            return 0;
        }

        $header = null;
        $data = array();

        if (($handle = fopen($CSVFile,'r')) !== false){
            while (($row = fgetcsv($handle, 1000, ',')) !==false){
                if (!$header) {
                    $header = $row;
                    if ($header[0] !== 'name' || $header[1] !== 'city') {
                        $this->error('Your CSV file need to contain city and name header!');
                        return;
                    }
                }
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        $dataCount = count($data);

        // Get the exisiting cities from DB
        $citiesCache = DB::table('cities')->select('id', 'name as city')->pluck('city', 'id')->toArray();
        $attractionsCache = DB::table('attractions')->select('city_id', 'name')->pluck('name', 'city_id')->toArray();

        for ($i = 0; $i < $dataCount; $i ++){

            try {
                $city = $data[$i]['city'];
                $attraction = $data[$i]['name'];

                // Search for existing city, if city is not found in cache then insert new one
                if (! $cityId = array_search($city, $citiesCache)) {

                    $cityId = DB::table($cityTable)->insertGetId([
                        'name' => $city,
                        'created_at' => date("Y-m-d h:i:s"),
                        'updated_at' => date("Y-m-d h:i:s"),
                    ]);

                    // Push city to cache
                    $citiesCache[$cityId] = $city;

                }

                DB::table($attractionTable)->insert([
                    'name' => $attraction,
                    'city_id' => $cityId,
                    'created_at' => date("Y-m-d h:i:s"),
                    'updated_at' => date("Y-m-d h:i:s"),
                ]);

            } catch (\Exception $th) {

                $this->error('Skipping duplicates!');
            }

        }

        $this->info("Data added successfully");

        return 1;

    }
}
