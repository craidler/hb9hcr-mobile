<?php
namespace Application\Controller;

use Application\Feature\UsesConfig;
use Application\Feature\UsesSession;
use GlobIterator;
use HB9HCR\Base\Collection;
use Laminas\Config\Config;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;

abstract class AbstractController extends AbstractActionController implements UsesConfig, UsesSession
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
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param Config $config
     * @return $this
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return Container
     */
    public function getSession(): Container
    {
        return $this->session;
    }

    /**
     * @param Container $session
     * @return $this
     */
    public function setSession(Container $session)
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function fileAction()
    {
        if ($this->request->isPost()) {
            $action = explode(',', $this->params()->fromPost('action'));

            switch ($action[0]) {
                case 'create':
                    touch($this->makeFilename($this->params()->fromPost('filename')));
                    break;

                case 'delete':
                    unlink($this->getPath() . '/' . $action[1]);
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
            'prefix' => $this->getPrefix(),
            'selected' => $this->session->offsetExists('file') ? $this->session->offsetGet('file') : null,
            'collection' => $collection,
        ]);

        $view->setTemplate('application/file/index.phtml');

        return $view;
    }

    /**
     * @return Collection
     */
    protected function getCollection(): Collection
    {
        return $this->collection ?? $this->collection = Collection::load($this->getFilename());
    }

    /**
     * @return string
     */
    protected function getFilename(): string
    {
        $this->session->offsetSet('file', $this->session->offsetExists('file') ? $this->session->offsetGet('file') : null);

        return sprintf(
            '%s/%s',
            $this->getPath(),
            $this->session->offsetGet('file') ?? 'default.' . $this->config->get('extension')
        );
    }

    /**
     * @return string
     */
    protected function getPath(): string
    {
        return $this->config->get('path');
    }

    /**
     * @return string
     */
    protected function getPrefix(): string
    {
        return explode('\\', get_called_class())[0];
    }

    /**
     * @param string $filename
     * @return string
     */
    protected function makeFilename(string $filename)
    {
        return sprintf(
            '%s/%s.%s',
            $this->getPath(),
            str_replace('.' . $this->config->get('extension'), '', $filename),
            $this->config->get('extension')
        );
    }
}