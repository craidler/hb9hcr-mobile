<?php
namespace Roadbook\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RoadbookController extends AbstractActionController
{
    public function __construct() {
        $this->maps = $this->getServiceLocator()->get('GoogleMaps');
    }

    public function indexAction()
    {
        echo $this->maps;
        return new ViewModel();
    }
}
