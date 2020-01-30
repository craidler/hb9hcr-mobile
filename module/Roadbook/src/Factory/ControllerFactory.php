<?php
namespace Roadbook\Factory;

use Interop\Container\ContainerInterface;
use Roadbook\Controller\RoadbookController;
use Zend\ServiceManager\Factory\FactoryInterface;

class ControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RoadbookController($container->get('GoogleMaps'));
    }
}