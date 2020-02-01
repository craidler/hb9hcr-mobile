<?php
namespace Roadbook\Factory;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class MapsFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        \HB9HCR\Service\Map\Google::configure([
            'cache' => __DIR__ . '/../../../../public/data',
            'key' => 'AIzaSyASv1hxoflfS4zj9G0P9TZ6SRso5mjfqUc',
        ]);

        return \HB9HCR\Service\Map\Google::instance();
    }
}