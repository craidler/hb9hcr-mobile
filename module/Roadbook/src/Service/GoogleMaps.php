<?php
namespace Roadbook\Service;

use Laminas\Config\Config;
use Roadbook\Model\Distance;
use Roadbook\Model\Duration;
use Roadbook\Model\Map;
use Roadbook\Model\Route;
use Roadbook\Model\Waypoint;

/**
 * Class GoogleMaps
 */
class GoogleMaps
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @inheritdoc
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->config->get('key');
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->config->get('url');
    }

    /**
     * @param float  $latitude
     * @param float  $longitude
     * @param int    $zoom
     * @param string $type
     * @return string
     */
    public function getImage(float $latitude, float $longitude, int $zoom, string $type): string
    {
        $hash = md5(sprintf('%f.%f.%d.%s', $latitude, $longitude, $zoom, $type));
        $filename = sprintf('%s/map/%s.png', $this->config->get('data'), $hash);

        if (!file_exists($filename)) {
            file_put_contents($filename, file_get_contents(sprintf(
                '%s/staticmap?center=%f,%f&zoom=%d&size=600x600&maptype=%s&key=%s',
                $this->config->get('url'),
                $latitude,
                $longitude,
                $zoom,
                $type,
                $this->config->get('key')
            )));
        }

        return file_get_contents($filename);
    }

    /**
     * @param Map $map
     * @return string
     */
    public function image(Map $map): string
    {
        return '';
    }

    /**
     * @param Waypoint $origin
     * @param Waypoint $destination
     * @return Route
     */
    public function route(Waypoint $origin, Waypoint $destination): Route
    {
        $filename = sprintf('%s/%s/%s.json', $this->config->get(('path')), strtolower(basename(__CLASS__)), md5($origin->position . $destination->position));

        if (!file_exists($filename)) {
            $url = sprintf('%s/directions/json?origin=%s&destination=%s&key=%s', $this->getUrl(), $origin->position, $destination->position, $this->getKey());
            file_put_contents($filename, file_get_contents($url));
        }

        $data = json_decode(file_get_contents($filename), JSON_OBJECT_AS_ARRAY);
        $route = is_array($data['routes']) && array_key_exists(0, $data['routes']) ? $route = $data['routes'][0]['legs'][0] : ['distance' => ['value' => 0], 'duration' => ['value' => 0]];

        return Route::createFromArray([
            'distance' => Distance::createFromArray($route['distance']),
            'duration' => Duration::createFromArray($route['duration']),
        ]);
    }
}