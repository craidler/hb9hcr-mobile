<?php
namespace Logger\Controller;

use Exception;
use Application\Controller\AbstractController;
use Laminas\Http\Response;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Logger\Model\Entry;

/**
 * Class LoggerController
 * @package Logger
 */
class LoggerController extends AbstractController
{
    /**
     * @inheritdoc
     */
    protected $class = Entry::class;

    /**
     * @var array[]
     */
    protected $nmea = [];

    /**
     * @return Response|ViewModel
     * @throws Exception
     */
    public function indexAction()
    {
        // $collection = $this->getCollection()->reverse();
        return $this->getView(array_merge($this->getNmea('gga'), $this->getNmea('vtg'), [
            'interval' => $this->getInterval(),
            //'item' => $collection->first(),
            // 'page' => Page::createFromCollection($collection, 5, $this->params()->fromRoute('id', 0)),
            'gpsd' => system('ps -ef | grep -v grep | grep -c gpsd'),
            'feed' => system('ps -ef | grep -v grep | grep -c logger.php'),
        ]));
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

    /**
     * @return JsonModel
     */
    public function ajaxAction()
    {
        return new JsonModel(array_merge($this->getNmea('gga'), $this->getNmea('vtg')));
    }

    /**
     * @return Response
     */
    public function intervalAction()
    {
        $this->getSession()->offsetSet('interval', (int)$this->params()->fromRoute('id', 0));
        return $this->redirect()->toRoute('logger');
    }

    /**
     * @param string $type
     * @return mixed[]
     */
    protected function getNmea(string $type)
    {
        if (!array_key_exists($type, $this->nmea)) {
            $this->nmea[$type] = [];
            foreach (explode(';', file_get_contents($this->getPath() . '/' . $this->makeFilename($type))) as $kv) {
                $kv = explode(':', $kv);
                $this->nmea[$type][$kv[0]] = $kv[1];
            }
        }

        return $this->nmea[$type];
    }

    /**
     * @return int
     */
    protected function getInterval(): int
    {
        $session = $this->getSession();
        if (!$session->offsetExists('interval')) $session->offsetSet('interval', 0);
        return $this->getSession()->offsetGet('interval');
    }
}