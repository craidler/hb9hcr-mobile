<?php
namespace Roadbook\Service;

use Application\Feature\UsesConfig;
use Laminas\Config\Config;

/**
 * Class GoogleMaps
 */
class GoogleMaps implements UsesConfig
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @inheritdoc
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @inheritdoc
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }
}