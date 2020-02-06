<?php
namespace Roadbook\Model;

/**
 * Class Waypoint
 * @package Roadbook\Model
 * @property string $date
 * @property string $region
 * @property string $name
 * @property string $type
 */
class Waypoint extends Position
{
    const TYPE_CAMPING = 'camping';
    const TYPE_CHECKPOINT = 'checkpoint';

    const TYPES = [
        self::TYPE_CAMPING,
        self::TYPE_CHECKPOINT,
    ];

    /**
     * @param int $i
     * @return Map
     */
    public function map(int $i = 0): Map
    {
        return $this->maps()->offsetGet($i);
    }
    
    /**
     * @return Map[ArrayObject]
     */
    public function maps()
    {
        $maps = $this->offsetGet('maps');

        foreach ($maps as $i => $map) $maps[$i] = Map::createFromArray(array_merge([
            'type' => Map::TYPE_ROAD,
            'zoom' => 12,
            'markers' => [$this->position],
            'position' => $this->position,
        ], $map));

        return $maps;
    }
}
