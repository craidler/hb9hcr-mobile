<?php
namespace Application\Feature;

use Laminas\Session\Container;

/**
 * Interface UsesSession
 * @package Application\Feature
 */
interface UsesSession
{
    public function getSession(): Container;

    public function setSession(Container $container);
}