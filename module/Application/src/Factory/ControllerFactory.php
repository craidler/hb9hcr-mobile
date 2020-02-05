<?php
namespace Application\Factory;

use Application\Controller\AbstractController;
use Application\Feature\UsesConfig;
use Application\Feature\UsesMaps;
use Application\Feature\UsesSession;
use Interop\Container\ContainerInterface;
use Laminas\Config\Config;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;

/**
 * Class ControllerFactory
 * @package Application\Factory
 */
class ControllerFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = new Config($container->get('config'));
        $controller = new $requestedName;
        $namespace = explode('\\', $requestedName)[0];
        $classname = $namespace . '\\Module';

        if ($controller instanceof UsesConfig) {
            $controller->setConfig($config->get($classname));
        }

        if ($controller instanceof UsesSession) {
            $controller->setSession(new Container($namespace, $container->get(SessionManager::class)));
        }

        if ($controller instanceof UsesMaps) {
            $controller->setMaps($container->get('GoogleMaps'));
        }

        return $controller;
    }
}