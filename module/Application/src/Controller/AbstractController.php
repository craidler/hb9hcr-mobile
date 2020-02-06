<?php
namespace Application\Controller;

use Application\Feature\UsesConfig;
use Application\Feature\UsesSession;
use Application\Model\Page;
use GlobIterator;
// use HB9HCR\Base\Collection;
use Application\Model\Collection;
use Laminas\Config\Config;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Router\RouteMatch;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;

abstract class AbstractController extends AbstractActionController implements UsesConfig, UsesSession
{
    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Collection
     */
    protected $collection;

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
                    touch($this->getPath() . '/' . $this->makeFilename($this->params()->fromPost('filename')));
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

        $path = sprintf('%s/*.%s', $this->getPath(), $this->config->get('extension'));
        $collection = new GlobIterator($path);

        $view = $this->getView([
            'path' => $path,
            'selected' => $this->session->offsetExists('file') ? $this->session->offsetGet('file') : null,
            'collection' => $collection,
        ]);

        $view->setTemplate('application/file/index.phtml');

        return $view;
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function createAction()
    {
        $route = $this->getRouteMatch()->getMatchedRouteName();

        if ($this->request->isPost()) {
            $this->getCollection()->handle($this->params()->fromPost())->persist();
            return $this->redirect()->toRoute($route);
        }

        return $this->getView()->setTemplate(sprintf('%s/form', $route));
    }

    /**
     * @return \Laminas\Http\Response|ViewModel
     */
    public function gridAction()
    {
        if ($this->request->isPost()) {
            $this->getCollection()->handle($this->params()->fromPost())->persist();
            return $this->redirect()->refresh();
        }

        return $this->getView([
            'action' => 'grid',
            'page' => Page::createFromCollection($this->getCollection(), 10, $this->params()->fromRoute('id', 0)),
        ]);
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection ?? $this->collection = Collection::load($this->getPath() . '/' . $this->getFile());
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->getConfig()->get('extension', 'json');
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        $session = $this->getSession();
        if (!$session->offsetExists('file')) $session->offsetSet('file', basename($this->getFiles()[0]));
        return $session->offsetGet('file');
    }

    /**
     * @return array
     */
    public function getFiles(): array
    {
        $files = glob(sprintf('%s/*.%s', $this->getPath(), $this->getExtension()));
        if (!$files) $files[] = 'default.' . $this->getExtension();
        return $files;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->getConfig()->get('path') . '/' . strtolower($this->getPrefix());
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return explode('\\', get_called_class())[0];
    }

    /**
     * @return \Laminas\Router\RouteMatch
     */
    public function getRouteMatch(): RouteMatch
    {
        return $this->getEvent()->getRouteMatch();
    }

    /**
     * @param array $data
     * @return ViewModel
     */
    public function getView(array $data = []): ViewModel
    {
        return new ViewModel(array_merge([
            'action' => str_replace('index', null, $this->params()->fromRoute('action', 'index')),
            'prefix' => $this->getPrefix(),
            'file' => $this->getFile(),
        ], $data));
    }

    /**
     * @param string $filename
     * @return string
     */
    public function makeFilename(string $filename): string
    {
        return sprintf(
            '%s.%s',
            str_replace('.' . $this->getExtension(), '', empty($filename) ? 'default' : $filename),
            $this->getExtension()
        );
    }
}