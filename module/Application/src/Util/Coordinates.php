<?php
namespace Application\Util;

/**
 * Class Coordinates
 * @package Application\Util
 */
abstract class Coordinates
{
    const LATITUDE = 0;
    const LONGITUDE = 1;

    public static function dmsToDec(string $dms): float
    {
        return 0.0;
    }

    /**
     * Decimal degrees to
     * @param float $dec
     * @return string
     */
    public static function decToDms(float $dec): string
    {
        return '';
    }

    /**
     * @param float $gps
     * @param string $hdg
     * @param int $decimals
     * @return float
     */
    public static function gpsToDec(string $gps, string $hdg, int $decimals = 6): float
    {
        $fact = false !== stripos('NW', $hdg) ? 1 : -1;
        $pnt = stripos($gps, '.');
        $deg = (int)substr($gps, 0, $pnt - 2);
        $dec = substr($gps, $pnt - 2) / 60;
        return ($deg + $dec) * $fact;
    }
}