<?php
namespace Roadbook\Controller;

use Application\Controller\FileController;
use Application\Model\Page;
use Exception;
use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;
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
        if (!$collection->count()) return $this->redirect()->toUrl('/roadbook/create');
        if (!ctype_digit($id)) return $this->redirect()->toUrl('/roadbook/index/' . $collection->find($id, false));

        /**
         * @var Waypoint $item
         * @var Waypoint $prev
         * @var Waypoint $last
         */
        $skip = $this->config->get('calculation')->get('skip')->toArray();
        $item = $collection->find((int)$id);
        $last = $collection->prev($item);
        $prev = $item;

        do {
            $prev = $collection->prev($prev);
        }
        while (in_array($prev->type, $skip));

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
            'title' => sprintf('%s - %s', $item->region, $item->name),
            'page' => Page::createFromCollection($collection, 1, $this->params()->fromRoute('id', 0)),
            'item' => $item,
            'distance' => $this->maps()->getDistance($prev->position, $item->position),
            'duration' => $this->maps()->getDuration($prev->position, $item->position),
            'distance_last' => $this->maps()->getDistance($last->position, $item->position),
            'duration_last' => $this->maps()->getDuration($last->position, $item->position),
        ]);
    }

    public function gridAction()
    {
        return parent::gridAction();
    }

    /**
     * @return ViewModel
     */
    public function printAction()
    {
        return $this->getView();
    }
}