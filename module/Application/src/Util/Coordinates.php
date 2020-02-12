<?php
namespace Application\Util;

/**
 * Class Coordinates
 * @package Application\Util
 */
abstract class Coordinates
{
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
     * @return float
     */
    public static function gpsToDec(float $gps, string $hdg): float
    {
        return (substr($gps, 0, 3) + (substr($gps, 4) / 60)) * (false !== strpos('NW', strtoupper($hdg)) ? 1 : -1);
    }
}