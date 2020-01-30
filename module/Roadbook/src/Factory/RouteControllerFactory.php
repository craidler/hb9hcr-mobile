<?php
namespace Roadbook\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Roadbook\Controller\RouteController;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class RouteControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new RouteController($container->get('GoogleMaps'));
    }
}