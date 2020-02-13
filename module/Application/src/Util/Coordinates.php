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
     * @param int $decimals
     * @return float
     */
    public static function gpsToDec(string $gps, string $hdg, int $decimals = 6): float
    {
        $gps = str_pad($gps, 11, '0', STR_PAD_LEFT);
        $deg = (int)substr($gps, 0, 3);
        $dec = substr($gps, 4) / 60;
        return number_format(($deg + $dec) * (false !== strpos('NW', strtoupper($hdg)) ? 1 : -1), $decimals);
    }
}