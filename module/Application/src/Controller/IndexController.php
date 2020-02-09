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
        return $this->redirect()->toRoute('roadbook');
    }

    /**
     * @return ViewModel
     */
    public function shutdownAction()
    {
        $command = 'sudo shutdown -h -t 10';
        $output = shell_exec($command);

        return new ViewModel([
            'command' => $command,
            'output' => $output,
        ]);
    }
}
