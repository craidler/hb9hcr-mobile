<?php
namespace Roadbook\Controller;

use HB9HCR\Entity\Roadbook;
use HB9HCR\Entity\Waypoint;
use HB9HCR\Service\Map\Google;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RouteController extends AbstractActionController
{
    public function __construct(Google $maps)
    {
        $this->maps = $maps;
    }

    public function indexAction()
    {
        $filename = __DIR__ . '/../../data/rim.2020.json';
        $roadbook = Roadbook::load($filename, Waypoint::class);
        $selected = Waypoint::create();

        if ($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            $params = explode(',', $post->get('action'));

            switch ($params[0]) {
                case 'save':
                    $roadbook->update($post->toArray())->persist($filename);
                    return $this->redirect()->refresh();

                case 'move':
                    $roadbook->move($params[1], $params[2])->persist($filename);
                    return $this->redirect()->refresh();

                case 'delete':
                    $roadbook->delete($params[1])->persist($filename);
                    return $this->redirect()->refresh();

                case 'cancel':
                    return $this->redirect()->refresh();

                case 'edit':
                    $selected = $roadbook->item($params[1]);
                    break;
            }
        }

        return new ViewModel([
            'roadbook' => $roadbook,
            'selected' => $selected,
        ]);
    }
}
