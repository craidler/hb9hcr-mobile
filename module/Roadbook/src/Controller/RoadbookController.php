<?php
namespace Roadbook\Controller;

use Application\Controller\FileController;
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
     */
    public function indexAction()
    {
        $collection = $this->getCollection();
        if (!$collection->count()) return $this->redirect()->toRoute(null, ['action' => 'create'], [], true);

        return $this->getView([
            'title' => sprintf('Waypoint: %s - %s', $this->getItem()->region, $this->getItem()->name),
        ]);
    }
}