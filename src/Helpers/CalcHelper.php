<?php
/**
 * Created by PhpStorm.
 * User: dalius
 * Date: 19.5.17
 * Time: 16.34
 */

namespace App\Helpers;

class CalcHelper
{
    const EARTH_RADIUS = 6371000;

    public function getDistanceFromCoordinates($lat1, $lon1, $lat2, $lon2)
    {
        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return round(($angle * self::EARTH_RADIUS)/1000, 2);
    }
}
