<?php
namespace Logger\Controller;

use Exception;
use Application\Controller\FileController;
use Laminas\Http\Response;
use Laminas\View\Model\ViewModel;

/**
 * Class LoggerController
 * @package Logger
 */
class LoggerController extends FileController
{
    /**
     * @return Response|ViewModel
     * @throws Exception
     */
    public function indexAction()
    {
        return $this->getView([
            'gpsd' => system($this->config->get('check')->get('gpsd')),
            'gpsl' => system($this->config->get('check')->get('gpsl')),
        ]);
    }
}