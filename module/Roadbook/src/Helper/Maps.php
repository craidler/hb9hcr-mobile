<?php
namespace Roadbook\Helper;

use Laminas\View\Helper\AbstractHelper;
use Roadbook\Model\Map as Model;
use Roadbook\Service\GoogleMaps;

/**
 * Class Map
 * @package Roadbook\Helper
 */
class Maps extends AbstractHelper
{
    /**
     * @var Model[]
     */
    private $maps;

    /**
     * @var GoogleMaps
     */
    private $service;

    /**
     * @param Model[]
     * @return $this
     */
    public function __invoke(array $maps): self
    {
        $this->maps = $maps;
        return $this;
    }

    /**
     * @param GoogleMaps $service
     * @return $this
     */
    public function setService(GoogleMaps $service): self
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return string
     */
    public function image(): string
    {
        return $this->getView()->render('partial/maps.phtml', [
            'maps' => $this->maps,
            'service' => $this->service,
        ]);
    }
}