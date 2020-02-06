<?php
namespace Diary\Controller;

use Application\Controller\AbstractController;
use Laminas\View\Model\ViewModel;


class DiaryController extends AbstractController
{
    public function indexAction()
    {
        return $this->getView();
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function createAction()
    {
        if ($this->request->isPost()) {
            $this->getCollection()->handle($this->params()->fromPost())->persist();
            return $this->redirect()->refresh();
        }

        return $this->getView(['action' => 'create'])->setTemplate('diary/form');
    }
}