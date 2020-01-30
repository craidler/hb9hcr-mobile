<?php
namespace Roadbook\Factory;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;
use Zend\ServiceManager\Factory\FactoryInterface;

class GoogleMapsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        \HB9HCR\Service\Map\Google::configure([
            'cache' => __DIR__ . '/../../data',
            'key' => '',
        ]);

        return \HB9HCR\Service\Map\Google::instance();
    }
}