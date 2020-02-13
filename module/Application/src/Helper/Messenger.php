<?php
namespace Application\Helper;

use Laminas\Session\Container;
use Laminas\View\Helper\AbstractHelper;

/**
 * Class Messenger
 */
class Messenger extends AbstractHelper
{
    /**
     * @var Container
     */
    protected $session;

    /**
     * @return $this
     */
    public function __invoke(): self
    {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getView()->render('partial/messenger.phtml', $this->session->getArrayCopy());
    }

    /**
     * @param string $type
     * @return bool
     */
    public function has(string $type): bool
    {
        return $this->session->offsetExists($type);
    }

    /**
     * @return $this
     */
    public function clear(): self
    {
        $this->session->exchangeArray([]);
        return $this;
    }

    /**
     * @param Container $session
     * @return Messenger
     */
    public function setSession(Container $session): Messenger
    {
        $this->session = $session;
        return $this;
    }
}