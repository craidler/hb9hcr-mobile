<?php
namespace Application\Plugin;

use Laminas\Mvc\Controller\Plugin\AbstractPlugin;
use Laminas\Session\Container;

/**
 * Class Messenger
 *
 * @method Messenger error(string $message = null)
 * @method Messenger success(string $message = null)
 */
class Messenger extends AbstractPlugin
{
    /**
     * @var Container
     */
    protected $session;

    /**
     * @param Container $session
     * @return Messenger
     */
    public function setSession(Container $session): Messenger
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return $this
     */
    public function __invoke(): self
    {
        return $this;
    }

    /**
     * @param string $name
     * @param array  $params
     * @return $this
     */
    public function __call(string $name, array $params): self
    {
        $value = array_shift($params) ?? '';
        $this->session->offsetSet($name, $value);
        return $this;
    }
}