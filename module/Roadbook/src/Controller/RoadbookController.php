<?php
namespace Roadbook\Controller;

use HB9HCR\Entity\Map;
use HB9HCR\Entity\Waypoint;
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
    public function createAction()
    {
        if ($this->request->isPost()) {
            $params = explode(',', $this->params()->fromPost('action'));
            switch ($params[0]) {
                case 'save':
                    $this->collection->append($this->params()->fromPost())->persist();
                    break;
            }

            return $this->redirect()->refresh();
        }

        $view = new ViewModel([
            'maptypes' => Map::TYPES,
            'types' => Waypoint::TYPES,
        ]);

        $view->setTemplate('roadbook/form.phtml');

        return $view;
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
     * @return ViewModel
     */
    public function gridAction()
    {
        return new ViewModel([
            'collection' => $this->collection,
        ]);
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function indexAction()
    {
        if (!$this->collection->count()) {
            return $this->redirect()->toRoute(null, ['action' => 'create'], [], true);
        }

        $index = $this->params()->fromRoute('id', 0);

        if (!ctype_digit($index)) {
            return $this->redirect()->toUrl('/roadbook/index/' . $this->collection->index($index));
        }

        $page = $this->collection->page(1, $index);
        $waypoint = $page['collection']->item(0);

        return new ViewModel(array_merge([
            'waypoint' => $waypoint,
            'route' => $this->maps->route($this->collection->prev($waypoint), $waypoint),
            'maps' => $this->maps,
        ], $page));
    }
}
