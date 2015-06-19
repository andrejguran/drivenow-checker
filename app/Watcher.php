<?php

namespace DriveNowChecker;

use Illuminate\Database\Eloquent\Model;

class Watcher extends Model
{
    protected $fillable = ['user_id', 'address', 'fuel_level', 'model_name', 'fuel_type', 'refresh_period', 'distance', 'latitude', 'longtitude'];

    public static $refreshPeriods = [ 60 => '1m', 300 => '5m', 600 => '10m', 1800 => '30m', 3600 => '1h'];

    public static $modelNames = ['MINI', 'MINI Cabrio', 'MINI Clubman', 'MINI Countryman', 'MINI New', 'BMW 1er', 'BMW Active E', 'BMW X1'];

    public static $fuelTypes = ['D' => 'Diesel', 'P' => 'Petrol', 'E' => 'Electric'];

    public static $cities = [
        "6099" => "Berlin",
        "1774" => "Köln",
        "1293" => "Düsseldorf",
        "40065" => "Hamburg",
        "40758" => "London",
        "4604" => "München",
        "4259" => "San Francisco",
        "40468" => "Wien",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
