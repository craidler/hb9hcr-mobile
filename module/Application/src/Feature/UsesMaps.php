<?php
namespace Application\Feature;

use HB9HCR\Service\Map\Google as Maps;

/**
 * Interface UsesMaps
 * @package Application\Feature
 */
interface UsesMaps
{
    public function getMaps(): Maps;

    public function setMaps(Maps $maps);
}