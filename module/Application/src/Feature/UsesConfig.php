<?php
namespace Application\Feature;

use Laminas\Config\Config;

/**
 * Interface Configurable
 * @package Application\Feature
 */
interface UsesConfig
{
    public function getConfig(): Config;

    public function setConfig(Config $config);
}