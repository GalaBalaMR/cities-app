<?php

namespace App\Console\Commands;

use App\Models\City;
use Illuminate\Console\Command;

class GeocodeCitiesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:geocode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add latitude and longitude for data in cities table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cities = City::all();

        foreach ($cities as $city) {
            $address = $city->City_hall_address . ", " . $city->name . ", Slovakia";
            $result = app('geocoder')->geocode($address)->get();
            if(!empty($result[0])){
                $coordinates = $result[0]->getCoordinates();
                $lat = $coordinates->getLatitude();
                $long = $coordinates->getLongitude();
                $city->update([
                    'latitude' => $lat,
                    'longitude' => $long
                ]);

            }else{
                $city->update([
                    'latitude' => "Can't read address",
                    'longitude' => "Can't read address"
                ]);
            }
        }

        $this->info('success');
    }
}
