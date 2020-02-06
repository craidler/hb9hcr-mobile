<?php
namespace Roadbook\Model;

use Application\Model\Item;

/**
 * Class Position
 * @package Roadbook\Model
 * @property float $latitude
 * @property float $longitude
 * @property string $position
 */
abstract class Position extends Item
{
    /**
     * @return float
     */
    public function latitude()
    {
        return explode(',', $this->position)[0];
    }

    /**
     * @return float
     */
    public function longitude()
    {
        return explode(',', $this->position)[1];
    }
}