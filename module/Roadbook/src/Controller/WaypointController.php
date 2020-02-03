<?php
namespace Roadbook\Controller;

use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;

/**
 * Class WaypointController
 * @package Roadbook\Controller
 */
class WaypointController extends AbstractController
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('waypoint', ['action' => 'detail', 'id' => $this->roadbook->first()->id]);
    }

    /**
     * @return ViewModel
     */
    public function detailAction()
    {
        $item = $this->roadbook->item($this->params()->fromRoute('id'));

        return new ViewModel([
            'route' => $this->maps->route($this->roadbook->prev($item), $item),
            'item' => $item,
            'prev' => $this->roadbook->prev($item),
            'next' => $this->roadbook->next($item),
            'maps' => $this->maps,
        ]);
    }
}