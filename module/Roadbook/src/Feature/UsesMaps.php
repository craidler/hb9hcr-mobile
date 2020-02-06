<?php
namespace Roadbook\Feature;

use Roadbook\Service\GoogleMaps as Maps;

/**
 * Interface UsesMaps
 * @package Application\Feature
 */
interface UsesMaps
{
    public function getMaps(): Maps;

    public function setMaps(Maps $maps);
}