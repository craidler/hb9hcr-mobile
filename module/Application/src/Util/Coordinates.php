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

    /**
     * Sexagesimal to decimaldegree
     * @param string $dms
     * @param string $hdg
     * @return float
     */
    public static function dmsToDec(string $dms, string $hdg): float
    {
        return 0.0;
    }

    /**
     * Decimaldegree to sexagesimal
     * @param float $dec
     * @param int   $axis
     * @return string
     */
    public static function decToDms(float $dec, int $axis = self::LATITUDE): string
    {
        $hdg = self::LATITUDE == $axis ? (0 <= $dec ? 'N' : 'S') : (0 <= $dec ? 'E' : 'W');
        $dec = abs($dec);
        $deg = floor($dec);
        $min = floor(60 * ($dec - $deg));
        $sec = 3600 * ($dec - $deg) - 60 * $min;
        return sprintf('%d°%02d\'%s"%s', $deg, $min, str_pad(sprintf('%.1f', $sec), 4, '0', STR_PAD_LEFT), $hdg);
    }

    /**
     * NMEA gps to decimaldegree
     * @param string $gps
     * @param string $hdg
     * @param int    $decimals
     * @return float
     */
    public static function gpsToDec(string $gps, string $hdg, int $decimals = 6): float
    {
        $fact = false !== stripos('NE', $hdg) ? 1 : -1;
        $pnt = stripos($gps, '.');
        $deg = (int)substr($gps, 0, $pnt - 2);
        $dec = substr($gps, $pnt - 2) / 60;
        return ($deg + $dec) * $fact;
    }
}