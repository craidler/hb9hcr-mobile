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
    public function __invoke()
    {
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $html = $this->getView()->render('partial/messenger.phtml', $this->session->getArrayCopy());
        $this->session->exchangeArray([]);
        return $html;
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