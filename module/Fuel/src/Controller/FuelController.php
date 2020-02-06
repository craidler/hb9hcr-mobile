<?php
namespace Fuel\Controller;

use Exception;
use Application\Controller\AbstractController;
use Application\Model\Collection;
use Application\Model\Page;
use Laminas\View\Model\ViewModel;

/**
 * Class FuelController
 * @package Fuel
 */
class FuelController extends AbstractController
{
    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $collection = $this->getCollection();
        if (!$collection->count()) return $this->redirect()->toRoute('fuel', ['action' => 'create']);


        $first = $collection->first();
        $last = $collection->last();

        $calculation = [
            'volume' => 0,
            'distance' => $last && $first ? $last->odometer - $first->odometer : 0,
            'consumption' => 0,
        ];

        foreach ($collection as $i => $item) if (0 < $i) $calculation['volume'] += $item->volume;
        $calculation['consumption'] = $calculation['distance'] ? ($calculation['volume'] / $calculation['distance']) * 100 : 0;

        $calculated = Collection::createFromArray();

        foreach ($collection as $item) {
            try {
                $prev = $collection->prev($item);
            }
            catch (Exception $e) {
                $prev = null;
            }

            try {
                $last = $calculated->last();
            }
            catch (Exception $e) {
                $last = null;
            }

            $calculated->append([
                'date' => $item->date,
                'odometer' => $item->odometer,
                'distance' => $item === $first ? 0 : $item->odometer - ($prev ? $prev->odometer : 0),
                'distance_total' => $item === $first ? 0 : $item->odometer - $prev->odometer + ($last ? $last->distance_total : 0),
                'volume' => $item->volume,
                'volume_total' => $item === $first ? $item->volume : $item->volume + ($last ? $last->volume_total : 0),
                'consumption' => $item === $first ? 0 : ($item->volume / ($item->odometer - ($prev ? $prev->odometer : 0))) * 100,
            ]);
        }

        return $this->getView([
            'calculation' => $calculation,
            'page' => Page::createFromCollection($calculated->reverse(), 5, $this->params()->fromRoute('id', 0))
        ]);
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function createAction()
    {
        if ($this->request->isPost()) {
            $this->getCollection()->handle($this->params()->fromPost())->persist();
            return $this->redirect()->toRoute('fuel');
        }

        return $this->getView(['action' => 'create'])->setTemplate('fuel/form');
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function gridAction()
    {
        if ($this->request->isPost()) {
            $params = explode(',', $this->params()->fromPost('action'));

            switch ($params[0]) {
                case 'delete':
                    $this->getCollection()->delete($params[1])->persist();
                    break;
            }

            return $this->redirect()->refresh();
        }

        return new ViewModel(array_merge([], $this->getCollection()->reverse()->page(10, $this->params()->fromRoute('id'))));
    }
}