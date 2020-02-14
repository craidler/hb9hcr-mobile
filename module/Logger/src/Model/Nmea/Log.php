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
     * @inheritdoc
     */
    public static function createFromArray(array $data = null)
    {
        array_unshift(static::$fields, 'id');
        return parent::createFromArray(array_combine(array_keys(static::$fields), $data));
    }

    /**
     * @param Item $item
     * @return Item
     */
    public static function createFromItem(Item $item)
    {
        $data = [];
        foreach (static::$fields as $k) $data[$k] = $item->{$k};
        $data['utc'] = gmdate('YmdHis');
        return parent::createFromArray($data);
    }
}