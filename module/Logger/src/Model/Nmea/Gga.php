<?php
namespace Logger\Model\Nmea;

use Application\Model\Item;

/**
 * Class Gga
 * @package Logger\Model\Nmea
 */
class Gga extends Item
{
    /**
     * @inheritdoc
     */
    public static function createFromArray(array $data = null): Item
    {
        return parent::createFromArray(array_combine([
            'utc',
            'lat',
            'lat_i',
            'lon',
            'lon_i',
            'qual',
            'sat',
            'hdop',
            'alt',
            'alt_u',
            'geos',
            'geos_u',
            'dgps',
            'checksum',
        ], $data));
    }
}