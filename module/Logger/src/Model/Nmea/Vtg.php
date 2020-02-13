<?php
namespace Logger\Model\Nmea;

use Application\Model\Item;

/**
 * Class Vtg
 * @package Logger\Model\Nmea
 * @property float $course_t
 * @property float $course_m
 * @property float $speed_n
 * @property float $speed_m
 */
class Vtg extends Item
{
    /**
     * @inheritdoc
     */
    public static function createFromArray(array $data = null): Item
    {
        return parent::createFromArray(array_combine([
            'log',
            'type',
            'course_t',
            'course_tu',
            'course_m',
            'course_mu',
            'speed_n',
            'speed_nu',
            'speed_m',
            'speed_mu',
            'checksum',
        ], $data));
    }
}