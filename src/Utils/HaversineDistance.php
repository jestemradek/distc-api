<?php

namespace App\Utils;

class HaversineDistance
{

    // calculation formula is from wikipedia: https://en.wikipedia.org/wiki/Haversine_formula
    public static function getDistance(float $latitudeFrom, float $longitudeFrom, float $latitudeTo, float $longitudeTo): float
    {
        $d2r = M_PI / 180; // degrees to radians
        $radius = 6371; // average earth radius in km
        
        $latFrom = $latitudeFrom * $d2r;
        $latTo = $latitudeTo * $d2r;
        $longFrom = $longitudeFrom * $d2r;
        $longTo = $longitudeTo * $d2r;

        $diffLat = $latTo - $latFrom;
        $diffLong = $longTo - $longFrom;

        $a = pow(sin($diffLat / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($diffLong / 2), 2);
        $distance = 2 * $radius * asin(sqrt($a));

        $distance = round($distance, 3);
        return $distance;
    }
}