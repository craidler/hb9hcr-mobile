<?php
namespace Application\Factory;

use Interop\Container\ContainerInterface;
use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;

/**
 * Class PluginFactory
 */
class PluginFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     * @return AbstractPlugin
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $plugin = new $requestedName;

        if (method_exists($plugin, 'setSession')) $plugin->setSession(new Container($requestedName, $container->get(SessionManager::class)));

        return $plugin;
    }
}