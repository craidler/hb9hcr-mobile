<?php
namespace Roadbook\Controller;

use HB9HCR\Entity\Roadbook;
use HB9HCR\Service\Map\Google as MapService;
use Laminas\Config\Config;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;
use Roadbook\Module;

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
     * @var Roadbook
     */
    protected $roadbook;

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

        if ($this->session->offsetExists('file')) {
            $this->roadbook = Roadbook::load(sprintf('%s/%s', $this->getDirectory(), $this->session->offsetGet('file')));
        }
    }

    /**
     * @return string
     */
    protected function getDirectory(): string
    {
        return $this->config->get('path');
    }
}