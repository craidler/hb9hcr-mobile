<?php
namespace Roadbook\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class MapsFactory implements FactoryInterface
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