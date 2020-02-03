<?php
namespace Roadbook\Controller;

use HB9HCR\Base\Collection;
use HB9HCR\Entity\Waypoint;
use HB9HCR\Service\Map\Google as MapService;
use Laminas\Config\Config;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;

abstract class AbstractController extends AbstractActionController
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var MapService
     */
    protected $maps;

    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var Container
     */
    protected $session;

    /**
     * AbstractController constructor.
     * @param Config $config
     * @param Container $session
     * @param MapService $maps
     */
    public function __construct(Config $config, Container $session, MapService $maps)
    {
        $this->config = $config;
        $this->session = $session;
        $this->maps = $maps;
        $this->collection = Collection::load($this->getFilename(), Waypoint::class);
    }

    /**
     * @return string
     */
    protected function getFilename()
    {
        return sprintf('%s/%s', $this->config->get('path'), $this->session->offsetGet('file'));
    }
}