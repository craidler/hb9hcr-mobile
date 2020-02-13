<?php
namespace Logger\Model;

use Application\Model\Item;

/**
 * Class Entry
 */
class Entry extends Item
{
    /**
     * @param array|null $data
     * @return Item
     */
    public static function createFromArray(array $data = null): Item
    {
        array_unshift($data, time());
        $class = __NAMESPACE__ . '\\Nmea\\' . ucfirst(strtolower($data[0]));
        return call_user_func_array([$class, 'createFromArray'], [$data]);
    }
}