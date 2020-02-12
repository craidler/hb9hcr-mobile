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
            'type',
            'utc',
            'latitude',
            'latitude_indicator',
            'longitude',
            'longitude_indicator',
            'quality',
            'satellites',
            'hdop',
            'altitude',
            'separation',
            'dgps_id',
            'checksum',
        ], $data));
    }
}