<?php
namespace Roadbook\Controller;

use Laminas\View\Model\ViewModel;

/**
 * Class RoadbookController
 * @package Roadbook\Controller
 */
class RoadbookController extends AbstractController
{
    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function indexAction()
    {
        $page = $this->collection->page(1, $this->params()->fromRoute('id'));
        $waypoint = $page['collection']->item(0);

        return new ViewModel(array_merge([
            'waypoint' => $waypoint,
            'route' => $this->maps->route($this->collection->prev($waypoint), $waypoint),
            'maps' => $this->maps,
        ], $page));
    }
}
