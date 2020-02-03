<?php
namespace Application\Controller;

use GlobIterator;
use Laminas\Config\Config;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;

abstract class AbstractFileController extends AbstractActionController
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Container
     */
    protected $session;

    /**
     * FileController constructor.
     * @param Config $config
     * @param Container $session
     */
    public function __construct(Config $config, Container $session)
    {
        $this->config = $config;
        $this->session = $session;
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $action = explode(',', $this->params()->fromPost('action'));

            switch ($action[0]) {
                case 'create':
                    touch($this->makeFilename($this->params()->fromPost('filename')));
                    break;

                case 'delete':
                    unlink($this->makeFilename($action[1]));
                    break;

                case 'select':
                    $this->session->offsetSet('file', $action[1]);
                    break;
            }

            return $this->redirect()->toUrl($this->request->getUriString());
        }

        $path = sprintf('%s/*.%s', $this->config->get('path'), $this->config->get('extension'));
        $collection = new GlobIterator($path);

        $view = new ViewModel([
            'path' => $path,
            'prefix' => $this->config->get('prefix'),
            'selected' => $this->session->offsetExists('file') ? $this->session->offsetGet('file') : null,
            'collection' => $collection,
        ]);

        $view->setTemplate('application/file/index.phtml');

        return $view;
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function makeFilename(string $filename)
    {
        return sprintf(
            '%s/%s.%s',
            $this->config->get('path'),
            str_replace('.' . $this->config->get('extension'), '', $filename),
            $this->config->get('extension')
        );
    }
}