<?php

namespace Roadbook\Controller;

use HB9HCR\Base\Collection;
use HB9HCR\Entity\Roadbook;
use HB9HCR\Entity\Waypoint;
use Laminas\View\Model\ViewModel;

class RoadbookController extends AbstractController
{
    /**
     * @var Roadbook
     */
    protected $roadbook;

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $params = explode(',', $this->params()->fromPost('action'));

            switch ($params[0]) {
                case 'create':
                    $item = Roadbook::create();
                    $item->persist($this->makeFilename($this->params()->fromPost('filename')));
                    break;

                case 'delete':
                    unlink($this->makeFilename($params[1]));
                    break;

                case 'select':
                    $this->session->offsetSet('filename', $params[1]);
                    break;
            }

            return $this->redirect()->refresh();
        }

        $items = glob($this->getDirectory() . '/*.json');
        array_walk($items, function (&$item) { $item = basename($item); });

        return new ViewModel([
            'selected' => $this->session->offsetExists('filename') ? $this->session->offsetGet('filename') : null,
            'items' => $items
        ]);
    }

    /**
     * @return ViewModel
     */
    public function roadbookAction()
    {
        $roadbook = $this->getRoadbook();
        $types = $this->getTypes();

        $roadbook = $roadbook->filter(function (Waypoint $waypoint) use ($types) {
            return $types[$waypoint->type];
        });

        return new ViewModel([
            'roadbook' => $roadbook,
            'maps' => $this->maps,
        ]);
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function routeAction()
    {
        $roadbook = $this->getRoadbook();
        $selected = Waypoint::create();
        $types = $this->getTypes();

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $params = explode(',', $post->get('action'));

            switch ($params[0]) {
                case 'export':
                    return $this->redirect()->toRoute('roadbook', ['action' => 'export']);

                case 'filter':
                    $types[$post['filter']['type']] = !$types[$post['filter']['type']];
                    $this->session->offsetSet('type', $types);
                    return $this->redirect()->refresh();

                case 'save':
                    $roadbook->update($post->toArray())->persist();
                    return $this->redirect()->refresh();

                case 'move':
                    $roadbook->move($params[1], $params[2])->persist();
                    return $this->redirect()->refresh();

                case 'delete':
                    $roadbook->delete($params[1])->persist();
                    return $this->redirect()->refresh();

                case 'cancel':
                    return $this->redirect()->refresh();

                case 'edit':
                    $selected = $roadbook->item($params[1]);
                    break;

                case 'view':
                    return $this->redirect()->toRoute('roadbook', ['action' => 'waypoint', 'id' => $params[1]]);
            }
        }

        $roadbook = $roadbook->filter(function (Waypoint $waypoint) use ($types) {
            return $types[$waypoint->type];
        });

        return new ViewModel([
            'roadbook' => $roadbook,
            'selected' => $selected,
            'types' => $types,
            'maps' => $this->maps,
        ]);
    }

    /**
     * @return array
     */
    protected function getTypes(): array
    {
        $types = array_fill_keys(Waypoint::TYPES, 1);

        if ($this->session->offsetExists('type')) {
            $types = $this->session->offsetGet('type');
        }

        return $types;
    }
}
