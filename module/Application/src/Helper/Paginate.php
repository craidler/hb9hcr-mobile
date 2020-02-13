<?php
namespace Application\Helper;

use Application\Model\Page;
use Laminas\Router\RouteMatch;
use Laminas\View\Helper\AbstractHelper;

/**
 * Class Paginate
 * @package Application\Helper
 */
class Paginate extends AbstractHelper
{
    /**
     * @var int
     */
    private $col = 3;

    /**
     * @var Page
     */
    private $page;

    /**
     * @var RouteMatch
     */
    private $match;

    /**
     * @param Page|null $page
     * @return $this
     */
    public function __invoke(Page $page = null): self
    {
        if ($page) $this->page = $page;
        return $this;
    }

    /**
     * @param RouteMatch $match
     * @return $this
     */
    public function setMatch(RouteMatch $match): self
    {
        $this->match = $match;
        return $this;
    }

    /**
     * @param int $col
     * @return $this
     */
    public function col(int $col): self
    {
        $this->col = $col;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->page ? $this->getView()->render('partial/paginate.phtml', [
            'current' => $this->page->current,
            'pages' => $this->page->pages,
            'first' => $this->page->first,
            'last' => $this->page->last,
            'next' => $this->page->next,
            'prev' => $this->page->prev,
            'url' => sprintf('%s/%s', $this->match->getMatchedRouteName(), $this->match->getParam('action')),
        ]) : '';
    }
}