<?php
namespace Roadbook\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Config\Config;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Roadbook\Module;

class ControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            (new Config($container->get(('config'))))->get(Module::class),
            new Container(Module::class, $container->get(SessionManager::class)),
            $container->get('MapService')
        );
    }
}