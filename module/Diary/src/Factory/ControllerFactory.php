<?php
namespace Diary\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Config\Config;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;
use Diary\Module;

class ControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new $requestedName(
            (new Config($container->get(('config'))))->get(Module::class),
            new Container(Module::class, $container->get(SessionManager::class))
        );
    }
}