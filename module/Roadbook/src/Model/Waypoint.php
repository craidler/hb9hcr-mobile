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
    const TYPE_BIVOUAC = 'bivouac';
    const TYPE_CAMPING = 'camping';
    const TYPE_CHECKPOINT = 'checkpoint';
    const TYPE_SUPPLY = 'supply';
    const TYPE_ORIGIN = 'origin';
    const TYPE_DESTINATION = 'destination';

    const TYPES = [
        self::TYPE_SUPPLY,
        self::TYPE_CHECKPOINT,
        self::TYPE_CAMPING,
        self::TYPE_BIVOUAC,
        self::TYPE_ORIGIN,
        self::TYPE_DESTINATION,
    ];

    /**
     * @inheritdoc
     */
    public static function createFromArray(array $data = null)
    {
        $data['position'] = str_replace([' ', '!4d'], ['', ','], $data['position']);
        return parent::createFromArray($data);
    }

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
