<?php
namespace Logger\Model\Nmea;

use Application\Model\Item;
use DateTime;
use Laminas\Validator\Date;

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
        $keys = static::$fields;
        array_unshift($keys, 'id');
        return parent::createFromArray(array_combine($keys, $data));
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

    /**
     * @return DateTime
     */
    public function datetime(): DateTime
    {
        return DateTime::createFromFormat('YmdHis', $this->utc);
    }
}