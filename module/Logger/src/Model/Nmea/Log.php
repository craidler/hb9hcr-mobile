<?php
namespace Logger\Model\Nmea;

use Application\Model\Item;

/**
 * Class Log
 * @package Logger\Model\Nmea
 */
class Log extends Item
{
    /**
     * @var string[]
     */
    protected static $fields = [
        'utc',
        'sat',
        'lat',
        'lat_i',
        'lon',
        'lon_i',
        'alt',
        'alt_u',
        'hdop',
        'dgps',
        'qual',
        'geos',
        'geos_u',
        'course_t',
        'course_tu',
        'course_m',
        'course_mu',
        'speed_m',
        'speed_mu',
        'speed_n',
        'speed_nu',
    ];

    /**
     * @param Item $item
     * @return Log
     */
    public static function createFromItem(Item $item): Log
    {
        $data = [];
        foreach (static::$fields as $k) $data[$k] = $item->{$k};
        return static::createFromArray($data);
    }
}