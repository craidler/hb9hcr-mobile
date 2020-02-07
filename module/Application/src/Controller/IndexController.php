<?php
namespace Application\Controller;

use Laminas\Mvc\Controller\AbstractActionController;

/**
 * Class IndexController
 */
class IndexController extends AbstractActionController
{
    /**
     * @return \Laminas\Http\Response|\Laminas\View\Model\ViewModel
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('roadbook');
    }
}
