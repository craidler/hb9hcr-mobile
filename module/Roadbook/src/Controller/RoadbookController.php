<?php
namespace Roadbook\Controller;

use Application\Controller\FileController;
use Application\Model\Page;
use Exception;
use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;
use Roadbook\Model\Waypoint;

/**
 * Class RoadbookController
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
         */
        $item = $this->getItem();
        $prev = $collection->prev($item);
        return $this->getView([
            'title' => sprintf('Waypoint: %s - %s', $item->region, $item->name),
            'page' => Page::createFromCollection($collection, 1, $this->params()->fromRoute('id', 0)),
            'item' => $item,
        ]);
    }
}