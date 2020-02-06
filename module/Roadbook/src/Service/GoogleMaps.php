<?php
namespace Roadbook\Service;

use Application\Feature\UsesConfig;
use Laminas\Config\Config;
use Roadbook\Model\Distance;
use Roadbook\Model\Duration;
use Roadbook\Model\Route;
use Roadbook\Model\Waypoint;

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

    /**
     * @param Waypoint $origin
     * @param Waypoint $destination
     * @return Route
     */
    public function route(Waypoint $origin, Waypoint $destination): Route
    {
        $data = [
            'distance' => [],
            'duration' => [],
        ];

        return Route::createFromArray([
            'distance' => Distance::createFromArray($data['distance']),
            'duration' => Duration::createFromArray($data['duration']),
        ]);
    }
}