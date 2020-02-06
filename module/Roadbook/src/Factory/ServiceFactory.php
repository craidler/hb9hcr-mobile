<?php
namespace Roadbook\Factory;

use Application\Feature\UsesConfig;
use Interop\Container\ContainerInterface;
use Laminas\Config\Config;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Roadbook\Module;

/**
 * Class ServiceFactory
 */
class ServiceFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     * @return mixed|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $service = new $requestedName;

        if ($service instanceof UsesConfig) {
            $service->setConfig(new Config($container->get('config')[Module::class]));
        }

        return $service;
    }
}