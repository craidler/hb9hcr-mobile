<?php
namespace Fuel\Controller;

use Fuel\Model\FuelItem;
use HB9HCR\Base\Collection;
use Laminas\Config\Config;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;

/**
 * Class AbstractController
 * @package Log\Controller
 */
abstract class AbstractController extends AbstractActionController
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Container
     */
    protected $session;

    /**
     * @var Collection
     */
    protected $collection;

    public function __construct(Config $config, Container $session)
    {
        $this->config = $config;
        $this->session = $session;
        $this->collection = $collection = Collection::load($this->getFilename(), FuelItem::class);
    }

    /**
     * @return string
     */
    protected function getFilename()
    {
        return sprintf('%s/%s', $this->config->get('path'), $this->session->offsetGet('file'));
    }
}