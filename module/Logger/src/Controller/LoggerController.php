<?php
namespace Logger\Controller;

use Application\Util\Coordinates;
use Exception;
use Application\Controller\FileController;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Logger\Model\Entry;

/**
 * Class LoggerController
 * @package Logger
 */
class LoggerController extends FileController
{
    public function ajaxAction()
    {
        $config = $this->config->get('nmea');
        $stream = fopen($config->get('device'), 'r');
        $needles = $config->get('types')->toArray();
        $pattern = sprintf('#^\$.{2}(%s)\,#', implode('|', $needles));
        $data = [];
        $i = 0;

        do {
            $line = trim(fgets($stream));
            if (!preg_match($pattern, $line, $match)) continue;
            $chunks = explode(',', $line);
            $chunks[0] = $match[1];
            $data = array_merge($data, Entry::createFromArray($chunks)->getArrayCopy());
            $i++;
        }
        while ($i < count($needles));
        foreach (['lat', 'lon'] as $k) $data[$k] = Coordinates::gpsToDec($data[$k], $data[$k . '_i']);
        return new JsonModel($data);
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