<?php
namespace Fuel\Controller;

use HB9HCR\Base\Collection;
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
        $first = $this->collection->first();
        $last = $this->collection->last();

        $calculation = [
            'volume' => 0,
            'distance' => $last && $first ? $last->odometer - $first->odometer : 0,
            'consumption' => 0,
        ];

        foreach ($this->collection as $i => $item) if (0 < $i) $calculation['volume'] += $item->volume;
        $calculation['consumption'] = $calculation['distance'] ? ($calculation['volume'] / $calculation['distance']) * 100 : 0;

        $collection = Collection::create();

        foreach ($this->collection as $item) {
            $prev = $this->collection->prev($item);
            $last = $collection->last();
            $collection->append([
                'date' => $item->date,
                'odometer' => $item->odometer,
                'distance' => $item === $first ? 0 : $item->odometer - $prev->odometer,
                'volume' => $item->volume,
                'volume_total' => $item === $first ? $item->volume : $item->volume + $last->volume_total,
                'consumption' => $item === $first ? 0 : ($item->volume / ($item->odometer - $prev->odometer)) * 100,
            ]);
        }

        return new ViewModel(array_merge([
            'calculation' => $calculation,
        ], $collection->reverse()->page(5, $this->params()->fromRoute('id', 0))));
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function createAction()
    {
        if ($this->request->isPost()) {
            $this->collection->append($this->params()->fromPost());
            $this->collection->persist();
            return $this->redirect()->toRoute('fuel');
        }

        return new ViewModel([

        ]);
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
                    $this->collection->delete($params[1])->persist();
                    break;
            }

            return $this->redirect()->refresh();
        }

        return new ViewModel(array_merge([], $this->collection->reverse()->page(10, $this->params()->fromRoute('id'))));
    }
}