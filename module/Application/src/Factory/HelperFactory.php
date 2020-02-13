<?php
namespace Application\Factory;

use Application\Helper\Messenger;
use Application\Plugin\Messenger as Plugin;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\Container;
use Laminas\Session\SessionManager;
use Laminas\View\Helper\AbstractHelper;

/**
 * Class HelperFactory
 */
class HelperFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     * @return AbstractHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new $requestedName;

        if ($helper instanceof Messenger) $helper->setSession(new Container(Plugin::class, $container->get(SessionManager::class)));

        return $helper;
    }
}