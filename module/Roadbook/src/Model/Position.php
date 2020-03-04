<?php
namespace Roadbook\Model;

use Application\Model\Item;
use Application\Util\Coordinates;

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

    /**
     * @return array
     */
    public function positionDD(): array
    {
        return explode(',', $this->position);
    }

    /**
     * @return array
     */
    public function positionDMS(): array
    {
        return [
            Coordinates::decToDms($this->latitude, Coordinates::LATITUDE),
            Coordinates::decToDms($this->longitude, Coordinates::LONGITUDE),
        ];
    }
}