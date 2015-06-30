<?php

namespace DriveNowChecker\Checkers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class Checker {

    protected static $cars = [];

    public function check($watcher)
    {
        if (!$this->overdue($watcher)) return false;

        $cars = $this->getCars($watcher);

        if (count($cars) > 0) {
            $this->mailCars($cars, $watcher);
        }

        $watcher->last_check = $this->now();
        $watcher->save();

        return true;
    }

    protected function mailCars($cars, $watcher)
    {
        $data = [
            'cars' => $cars,
            'watcher' => $watcher
        ];

        Mail::send('emails.cars', $data, function ($message) use($watcher) {
            $message->from('mailgun@sandbox5186412958054ea1b6efc0c3bac5286e.mailgun.org', 'Drive now checker');
            $message->to($watcher->user->email);
            $message->subject('Drive now ready cars in your area!');
        });
    }

    protected function overdue($watcher)
    {
        if ($watcher->last_check == null)
            return true;

        $now = $this->now();
        $last = (int)$watcher->last_check;

        $interval = abs($now - $last);

        if ($interval >= $watcher->refresh_period)
            return true;

        return false;
    }

    public function getCars($watcher)
    {
        $this->storeCityCars($watcher->user->city);

        $cars = $this->userFilterCars($watcher);

        $cars = $this->simpleDistanceFilterCars($watcher, $cars);

        $cars = $this->googleDistanceFilterCars($watcher, $cars);

        uasort($cars, function($a, $b){
            if ($a['walking_distance'] == $b['walking_distance']) return 0;
            return ($a['walking_distance'] < $b['walking_distance']) ? -1 : 1;
        });

        return $cars;
    }

    protected function storeCityCars($city)
    {
        if (isset($cars[$city])) return;
        $apiKey = Config::get('drivenowchecker.drivenow-api-key');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://api2.drive-now.com/cities/{$city}?expand=full");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["X-Api-Key: {$apiKey}", 'Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.124 Safari/537.36");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        $output = curl_exec ($ch);
        $info = curl_getinfo($ch);
        curl_close ($ch);

        $json = json_decode($output);

        foreach ($json->cars->items as $car) {
            self::$cars[$city][] = [
                'address' => implode($car->address),
                'fuel_level' => (int)$car->fuelLevelInPercent,
                'fuel_type' => $car->fuelType,
                'model_name' => $car->modelName,
                'latitude' => (float)$car->latitude,
                'longitude' => (float)$car->longitude,
            ];
        }
    }

    protected function userFilterCars($watcher)
    {
        return array_filter(self::$cars[$watcher->user->city], function($car) use ($watcher){
            if ((int)$car['fuel_level'] > (int)$watcher->fuel_level) return false;
            if ($watcher->fuel_type != null && $car['fuel_type'] != $watcher->fuel_type) return false;
            if ($watcher->model_name != null && $car['model_name'] != $watcher->model_name) return false;
            return true;
        });
    }

    protected function simpleDistanceFilterCars($watcher, $cars)
    {
        return array_filter($cars, function($car) use ($watcher){
            $distance = self::simpleWalkingDistance($car['latitude'], $car['longitude'], $watcher->latitude, $watcher->longtitude) <= ($watcher->distance * 2);
            return $distance;
        });
    }


    protected function now()
    {
        return (new \DateTime())->getTimestamp();
    }

    public static function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 4) {
        // Calculate the distance in degrees
        $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));

        // Convert the distance in degrees to the chosen unit (kilometres, miles or nautical miles)
        switch($unit) {
            case 'km':
                $distance = $degrees * 111.13384; // 1 degree = 111.13384 km, based on the average diameter of the Earth (12,735 km)
                break;
            case 'mi':
                $distance = $degrees * 69.05482; // 1 degree = 69.05482 miles, based on the average diameter of the Earth (7,913.1 miles)
                break;
            case 'nmi':
                $distance =  $degrees * 59.97662; // 1 degree = 59.97662 nautic miles, based on the average diameter of the Earth (6,876.3 nautical miles)
        }
        return round($distance, $decimals);
    }

    public static function simpleWalkingDistance($point1_lat, $point1_long, $point2_lat, $point2_long)
    {
        $km = self::distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long);

        //5km/h * 60min
        return $km / 5 * 60;
    }

    public static function googleDistanceFilterCars($watcher, $cars)
    {
        $cars = array_slice($cars, 0, 80); //max google allows
        $carsCoordinates = implode('|',array_map(function($car){
            return $car['latitude'].','.$car['longitude'];
        }, $cars));

        $googleApiKey = Config::get('drivenowchecker.google-api-key');
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($watcher['address'])."&destinations=".urlencode($carsCoordinates)."&sensor=false&key={$googleApiKey}";

        $json = json_decode(file_get_contents($url));

        if (!isset($json->rows[0])) {
            return [];
        }

        $carsDistances = [];

        foreach ($json->rows[0]->elements as $distance) {
            $carsDistances[] = (int)$distance->duration->value;
        }

        $closeCars = [];
        $i = 0;
        foreach ($cars as $car) {
            if (isset($carsDistances[$i]) && ($carsDistances[$i] <= ($watcher->distance * 60))) {
                $closeCars[] = array_merge($car, ['walking_distance' => $carsDistances[$i]]);
            }
            $i++;
        }

        return $closeCars;
    }
} 