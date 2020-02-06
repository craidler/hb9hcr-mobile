<?php
namespace Roadbook\Factory;

use Application\Factory\ControllerFactory as BaseControllerFactory;
use Interop\Container\ContainerInterface;
use Roadbook\Feature\UsesMaps;
use Roadbook\Service\GoogleMaps;

/**
 * Class ControllerFactory
 */
class ControllerFactory extends BaseControllerFactory
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $controller = parent::__invoke($container, $requestedName, $options);

        if ($controller instanceof UsesMaps) {
            $controller->setMaps($container->get(GoogleMaps::class));
        }

        return $controller;
    }
}