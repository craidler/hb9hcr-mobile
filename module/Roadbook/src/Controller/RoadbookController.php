<?php
namespace Roadbook\Controller;

use Application\Controller\FileController;
use Application\Model\Page;
use Exception;
use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;
use Roadbook\Model\Route;
use Roadbook\Model\Waypoint;
use Roadbook\Plugin\Maps;

/**
 * Class RoadbookController
 * @package Roadbook\Controller
 * @method Maps maps()
 */
class RoadbookController extends FileController
{
    /**
     * @inheritdoc
     */
    protected $class = Waypoint::class;

    /**
     * @return Response|ViewModel
     * @throws Exception
     */
    public function indexAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $collection = $this->getCollection();
        if (!$collection->count()) return $this->redirect()->toRoute(null, ['action' => 'create'], [], true);
        if (!is_numeric($id)) return $this->redirect()->toRoute(null, ['id' => $collection->find($id, false)], [], true);

        /**
         * @var Waypoint $item
         * @var Waypoint $prev
         */
        $item = $this->getItem();
        $prev = $collection->prev($item);

        if ($this->isPost()) {
            switch ($this->getFormAction()) {
                case 'check':
                    $item->check = true;
                    $collection->persist();
                    $this->message()->success();
                    break;
            }

            return $this->redirect()->refresh();
        }

        return $this->getView([
            'title' => sprintf('Waypoint: %s - %s', $item->region, $item->name),
            'page' => Page::createFromCollection($collection, 1, $this->params()->fromRoute('id', 0)),
            'item' => $item,
            'distance' => $this->maps()->getDistance($prev->position, $item->position),
            'duration' => $this->maps()->getDuration($prev->position, $item->position),
        ]);
    }
}