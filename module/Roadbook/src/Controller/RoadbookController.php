<?php
namespace Roadbook\Controller;

use HB9HCR\Entity\Roadbook;
use HB9HCR\Entity\Waypoint;
use HB9HCR\Service\Map\Google;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class RoadbookController extends AbstractActionController
{
    /**
     * @var Google
     */
    protected $maps;

    /**
     * @var Roadbook
     */
    protected $roadbook;

    /**
     * @param Google $maps
     */
    public function __construct(Google $maps)
    {
        $this->maps = $maps;
    }

    public function routeAction()
    {
        $roadbook = $this->getRoadbook();
        $selected = Waypoint::create();

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $params = explode(',', $post->get('action'));

            switch ($params[0]) {
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

        return new ViewModel([
            'roadbook' => $roadbook,
            'selected' => $selected,
        ]);
    }

    public function waypointAction()
    {
        $id = $this->params()->fromRoute('id');
        $waypoint = $this->getRoadbook()->item($id);

        if ($this->request->isPost() && $this->params()->fromPost('action')) {
            $params = explode(',', $this->params()->fromPost('action'));
            $post = $this->params()->fromPost();

            switch ($params[0]) {
                case 'save':
                    $waypoint->comment = $post['comment'];

                    foreach ($waypoint->maps as $i => $map) {
                        $map->zoom = $post['zoom'][$i];
                        $map->type = $post['type'][$i];
                    }

                    $this->getRoadbook()->persist();
                    return $this->redirect()->refresh();
            }
        }

        return new ViewModel([
            'maps' => $this->maps,
            'waypoint' => $waypoint,
        ]);
    }

    /**
     * @return Roadbook
     */
    protected function getRoadbook(): Roadbook
    {
        $filename = __DIR__ . '/../../data/rim.2020.json';
        return $this->roadbook = $this->roadbook ?? Roadbook::load($filename, Waypoint::class);
    }
}
