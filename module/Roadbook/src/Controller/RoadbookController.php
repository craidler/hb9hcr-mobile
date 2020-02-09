<?php
namespace Roadbook\Controller;

use Application\Controller\AbstractController;
use Application\Model\Collection;
use Application\Model\Page;
use Roadbook\Model\Waypoint;
use Roadbook\Service\GoogleMaps as Maps;
use Laminas\View\Model\ViewModel;
use Roadbook\Feature\UsesMaps;

/**
 * Class RoadbookController
 * @package Roadbook\Controller
 */
class RoadbookController extends AbstractController implements UsesMaps
{
    /**
     * @var Maps
     */
    private $maps;

    /**
     * @var string
     */
    protected $class = Waypoint::class;

    /**
     * @return Maps
     */
    public function getMaps(): Maps
    {
        return $this->maps;
    }

    /**
     * @param Maps $maps
     * @return $this
     */
    public function setMaps(Maps $maps)
    {
        $this->maps = $maps;
        return $this;
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function editAction()
    {
        $item = $this->collection->item($this->params()->fromRoute('id'));

        if ($this->request->isPost()) {
            $action = explode(',', $this->params()->fromPost('action'));
            switch ($action[0]) {
                case 'save':
                    $item->update($this->params()->fromPost());
                    $this->collection->persist();
                    break;
            }

            return $this->redirect()->refresh();
        }

        $view = new ViewModel([
            'maptypes' => Map::TYPES,
            'types' => Waypoint::TYPES,
            'item' => $item,
        ]);

        $view->setTemplate('roadbook/form.phtml');

        return $view;
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     * @throws \Exception
     */
    public function indexAction()
    {
        $collection = $this->getCollection();

        if (!$collection->count()) {
            return $this->redirect()->toRoute(null, ['action' => 'create'], [], true);
        }

        $waypoint = $collection->find($this->params()->fromRoute('id', 0));

        return $this->getView([
            'item' => $waypoint,
            'page' => Page::createFromCollection($collection, 1, $collection->find($waypoint, false)),
            'maps' => $this->getMaps(),
            'route' => $this->getMaps()->route($collection->prev($waypoint), $waypoint),
        ]);
    }

    /**
     * @return void
     */
    public function testAction()
    {
        $list = Collection::load(__DIR__ . '/default.json');
        /*
        $list->append(['id' => 0, 'name' => 'Christof']);
        $list->append(['id' => 1, 'name' => 'Christof']);
        $list->append(['id' => 2, 'name' => 'Christof']);
        $list->persist();
        exit;
        */
        $page = Page::createFromCollection($list, 1, 2);
        foreach (['records', 'pages', 'first', 'last', 'current', 'next', 'prev'] as $p) var_dump($page->{$p});
        exit;
    }
}
