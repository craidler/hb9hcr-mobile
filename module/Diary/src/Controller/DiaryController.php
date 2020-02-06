<?php
namespace Diary\Controller;

use Application\Controller\AbstractController;

class DiaryController extends AbstractController
{
    public function indexAction()
    {
        return $this->getView();
    }
}