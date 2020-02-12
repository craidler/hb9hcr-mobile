<?php
namespace Logger\Controller;

use Application\Model\Page;
use Exception;
use Application\Controller\AbstractController;
use Laminas\Http\Response;
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
     * @return Response|ViewModel
     * @throws Exception
     */
    public function indexAction()
    {
        // $collection = $this->getCollection()->reverse();
        $gga = explode(':', file_get_contents('/home/pi/hb9hcr-mobile/public/data/logger/gga.dat'));
        $vtg = explode(':', file_get_contents('/home/pi/hb9hcr-mobile/public/data/logger/vtg.dat'));

        return $this->getView([
            'interval' => $this->getSession()->offsetGet('interval') ?? 0,
            'position' => $gga[1],
            'speed' => $vtg[3],
            'altitude' => $gga[3],
            //'item' => $collection->first(),
            // 'page' => Page::createFromCollection($collection, 5, $this->params()->fromRoute('id', 0)),
            'gpsd' => system('ps -ef | grep -v grep | grep -c gpsd'),
            'feed' => system('ps -ef | grep -v grep | grep -c logger.php'),
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

    public function intervalAction()
    {
        $this->getSession()->offsetSet('interval', $this->params()->fromRoute('id', 0));
        return $this->redirect()->toRoute('logger');
    }
}