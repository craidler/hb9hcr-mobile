<?php
namespace Logger\Model;

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