<?php
namespace Logger\Model;

use Exception;
use Application\Model\Item;

/**
 * Class Entry
 *
 * @property int $utc
 * @property float $alt
 * @property string $date
 * @property string $time
 * @property string $position
 * @property float $latitude
 * @property float $longitude
 * @property float $speed
 * @property int $sats
 */
class Entry extends Item
{
    /**
     * @param string $sentence
     * @return Item
     * @throws Exception
     */
    public static function createFromNMEA(string $sentence): Item
    {
        $words = explode(',', $sentence);
        if (!count($words)) throw new Exception('failed to parse sentence ' . $sentence);
        $words[0] = strtoupper(substr($words[0], 3));
        $class = __NAMESPACE__ . '\\Nmea\\' . ucfirst(strtolower($words[0]));
        return call_user_func_array([$class, 'createFromArray'], [$words]);
    }

    /**
     * @return string
     */
    public function date(): string
    {
        return date('Y-m-d', $this->utc);
    }

    /**
     * @return string
     */
    public function time(): string
    {
        return date('H:i:s', $this->utc);
    }

    /**
     * @return float
     */
    public function latitude(): float
    {
        return (float)explode(',', $this->position)[0];
    }

    /**
     * @return float
     */
    public function longitude(): float
    {
        return (float)explode(',', $this->position)[1];
    }
}