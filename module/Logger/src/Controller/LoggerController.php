<?php
namespace Logger\Controller;

use Application\Util\Coordinates;
use Exception;
use Application\Controller\FileController;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Logger\Model\Nmea\Log;
use Logger\Service\Nmea;

/**
 * Class LoggerController
 * @package Logger
 */
class LoggerController extends FileController
{
    /**
     * @inheritdoc
     */
    protected $class = Log::class;

    /**
     * @return JsonModel
     */
    public function ajaxAction()
    {
        $data = [
            'lat' => 0,
            'lon' => 0,
        ];

        try {
            $service = new Nmea($this->config->get('nmea'));
            $data = $service->collect()->getArrayCopy();

            foreach (['lat', 'lon'] as $k) $data[$k] = sprintf('%.07f', Coordinates::gpsToDec($data[$k], $data[$k . '_i']));
            foreach (['alt', 'speed_m'] as $k) $data[$k] = sprintf('%d', $data[$k]);
            foreach (['hdop'] as $k) $data[$k] = sprintf('%.01f', $data[$k]);
            foreach (['course_t', 'course_m'] as $k) $data[$k] = strlen($data[$k]) ? sprintf('%d', $data[$k]) : '---';
            foreach ($this->config->get('check')->toArray() as $k => $cmd) $data[$k] = exec($cmd);
        }
        catch (Exception $e) {
        }

        $data['lat_hms'] = Coordinates::decToDms($data['lat'], Coordinates::LATITUDE);
        $data['lon_hms'] = Coordinates::decToDms($data['lon'], Coordinates::LONGITUDE);

        return new JsonModel($data);
    }

    /**
     * @return Response|ViewModel
     * @throws Exception
     */
    public function indexAction()
    {
        return $this->getView();
    }
}