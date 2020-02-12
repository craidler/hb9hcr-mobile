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
        var_dump($data);

        return parent::createFromArray(array_combine([
            'type',
            'utc',
            'lat',
            'lat_i',
            'lon',
            'lon_i',
            'qual',
            'sats',
            'hdop',
            'alt',
            'geos',
            'dgps',
            'checksum',
        ], $data));
    }
}