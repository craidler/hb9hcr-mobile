<?php
namespace Application\Factory;

use HB9HCR\Service\Map\Google;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class MapsFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return Google|object
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        Google::configure($container->get('config')[$requestedName]);
        return Google::instance();
    }
}