<?php
namespace Logger\Controller;

use Application\Model\Page;
use Exception;
use Application\Controller\AbstractController;
use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;

/**
 * Class LoggerController
 * @package Logger
 */
class LoggerController extends AbstractController
{
    /**
     * @return Response|ViewModel
     * @throws Exception
     */
    public function indexAction()
    {
        return $this->getView([
            'interval' => 60,
            'page' => Page::createFromCollection($this->getCollection()->reverse(), 5, $this->params()->fromRoute('id', 0)),
        ]);
    }

    public function testAction()
    {
        $this->getCollection()->append([
            'date' => date('Y-m-d'),
            'time' => date('H:i:s'),
            'position' => '0,0',
            'speed' => 0,
        ])->persist();
    }

    public function startAction()
    {
        shell_exec('../../bin/logger.sh start');
    }

    public function stopAction()
    {
        shell_exec('../../bin/logger.sh stop');
    }
}