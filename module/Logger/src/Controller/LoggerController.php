<?php
namespace Logger\Controller;

use Exception;
use Application\Controller\FileController;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

/**
 * Class LoggerController
 * @package Logger
 */
class LoggerController extends FileController
{
    public function ajaxAction()
    {
        sleep(rand(0, 1));

        return new JsonModel([
            'course_m' => rand(0, 359),
            'course_t' => rand(0, 359),
            'alt' => rand(0, 445),
            'hdop' => rand(0, 2),
            'speed_m' => rand(0, 100),
            'lat' => rand(0, 90),
            'lat_u' => 'N',
            'lon' => rand(0, 90),
            'lon_u' => 'E',
        ]);
    }

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