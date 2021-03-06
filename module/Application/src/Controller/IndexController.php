<?php
namespace Application\Controller;

use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

/**
 * Class IndexController
 */
class IndexController extends AbstractActionController
{
    /**
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute('logger');
    }

    /**
     * @return ViewModel
     */
    public function shutdownAction()
    {
        $command = 'sudo shutdown +0';
        $output = shell_exec($command);

        return new ViewModel([
            'command' => $command,
            'output' => $output,
        ]);
    }
}
