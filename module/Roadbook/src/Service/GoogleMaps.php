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
        $hash = md5(sprintf('%f:%f:%d:%s', $latitude, $longitude, $zoom, $type));
        $filename = sprintf('%s/map/%s.png', $this->config->get('data'), $hash);

        if (!file_exists($filename)) {
            file_put_contents($filename, file_get_contents(sprintf(
                '%1$s/staticmap?center=%2$f,%3$f&markers=%2$f,%3$f&zoom=%4$d&size=600x600&maptype=%5$s&key=%6$s',
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
     * @param string $origin
     * @param string $destination
     * @return string
     */
    public function getRoute(string $origin, string $destination): string
    {
        $hash = md5(sprintf('%s:%s', $origin, $destination));
        $filename = sprintf('%s/route/%s.json', $this->config->get('data'), $hash);

        if (!file_exists($filename)) {
            file_put_contents($filename, file_get_contents(sprintf(
                '%s/directions/json?origin=%s&destination=%s&key=%s',
                $this->config->get('url'),
                $origin,
                $destination,
                $this->config->get('key')
            )));
        }

        return file_get_contents($filename);
    }
}