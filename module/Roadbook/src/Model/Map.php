<?php
namespace Roadbook\Model;

/**
 * Class Map
 * @package Roadbook\Model
 * @property int $type
 * @property int $zoom
 */
class Map extends Position
{
    const TYPE_ROAD = 'road';
    const TYPE_SATELLITE = 'satellite';
    const TYPE_TERRAIN = 'terrain';
    const TYPE_HYBRID = 'hybrid';

    const TYPES = [
        self::TYPE_ROAD,
        self::TYPE_SATELLITE,
        self::TYPE_TERRAIN,
        self::TYPE_HYBRID,
    ];
}