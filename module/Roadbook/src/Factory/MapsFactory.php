<?php
namespace Roadbook\Factory;

use HB9HCR\Service\Map\Google;
use Interop\Container\ContainerInterface;
use Laminas\Config\Config;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Roadbook\Module;

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
        $config = (new Config($container->get(('config'))))->get(Module::class);

        Google::configure([
            'cache' => $config->get('data'),
            'key' => $config->get('key'),
        ]);

        return Google::instance();
    }
}