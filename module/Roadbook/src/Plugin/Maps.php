<?php
namespace Roadbook\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Roadbook\Service\GoogleMaps;

/**
 * Class Maps
 * @method int getDistance(string $origin, string $destination)
 * @method int getDuration(string $origin, string $destination)
 * @method string getImage(float $latitude, float $longitude, int $zoom, string $type)
 * @method string getRoute(string $origin, string $destination)
 */
class Maps extends AbstractPlugin
{
    /**
     * @var GoogleMaps
     */
    protected $service;

    /**
     * @param GoogleMaps $service
     * @return Maps
     */
    public function setService(GoogleMaps $service): Maps
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return $this
     */
    public function __invoke(): self
    {
        return $this;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->service, $name], $arguments);
    }
}