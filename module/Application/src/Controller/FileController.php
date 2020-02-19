<?php
namespace Application\Controller;

use Application\Model\Page;
use Application\Plugin\Messenger;
use Exception;
use Application\Model\Collection;
use Application\Model\Item;
use FilesystemIterator, GlobIterator;
use Laminas\Config\Config;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Session\Container;
use Laminas\View\Model\ViewModel;

/**
 * Class FileController
 *
 * @method Messenger message()
 */
abstract class FileController extends AbstractActionController
{
    /**
     * @var string
     */
    protected $class = Item::class;

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
     * @return Response|ViewModel
     */
    public function createAction()
    {
        if ($this->isPost()) {
            $this->getCollection()->append($this->getFormData())->persist();
            $this->message()->success();
            return $this->redirect()->toRoute(null, ['action' => 'index'], [], true);
        }

        return $this->getView()->setTemplate(sprintf('%1$s/%1$s/form', $this->getNamespace()));
    }

    public function editAction()
    {
        if ($this->isPost()) {
            $this->getCollection()->update($this->params()->fromRoute('id'), $this->getFormData())->persist();
            $this->message()->success();
            return $this->redirect()->refresh();
        }

        return $this->getView(['item' => $this->getItem()])->setTemplate(sprintf('%1$s/%1$s/form', $this->getNamespace()));
    }

    /**
     * @return Response|ViewModel
     */
    public function fileAction()
    {
        if ($this->isPost()) {
            switch ($this->getFormAction()) {
                case 'create':
                    touch($this->getFilename($this->getFormData()['filename']));
                    $this->message()->success();
                    break;

                case 'delete':
                    $filename = $this->getFilename($this->getFormId());
                    if ($filename == $this->getFile()) $this->session->offsetUnset('file');
                    unlink($filename);
                    $this->message()->success();
                    break;

                case 'select':
                    $this->session->offsetSet('file', $this->getFilename($this->getFormId()));
                    return $this->redirect()->toRoute(null, ['action' => 'index'], [], true);
            }

            return $this->redirect()->refresh();
        }

        return $this->getView()->setTemplate('application/file/index');
    }

    /**
     * @return Response|ViewModel
     */
    public function gridAction()
    {
        if (!$this->getCollection()->count()) return $this->redirect()->toRoute(null, ['action' => 'create'], [], true);

        if ($this->isPost()) {
            try {
                switch ($this->getFormAction()) {
                    case 'delete':
                        $this->getCollection()->delete($this->getFormId())->persist();
                        $this->message()->success();
                        break;
                }
            }
            catch (Exception $e) {
                $this->message()->error($e->getMessage());
            }

            return $this->redirect()->refresh();
        }

        return $this->getView([
            'page' => Page::createFromCollection($this->getCollection(), 10, $this->params()->fromRoute('id', 0)),
        ])->setTemplate(sprintf('%1$s/%1$s/grid', $this->getNamespace()));
    }

    /**
     * @param Config $config
     * @return FileController
     */
    public function setConfig(Config $config): FileController
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param Container $session
     * @return FileController
     */
    public function setSession(Container $session): FileController
    {
        $this->session = $session;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        if (!$this->collection) {
            $collection = Collection::load($this->getFile(), $this->getClass());
            $this->collection = $this->config->get('file')->get('reverse', false) ? $collection->reverse() : $collection;
        }
        return $this->collection;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        try {
            $item = $this->getCollection()->find($this->params()->fromRoute('id', 0));
        }
        catch (Exception $e) {
            $item = call_user_func_array([$this->getClass(), 'createFromArray'], [[]]);
        }

        return $item;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        if (!$this->session->offsetExists('file')) {
            $files = $this->getFiles();
            if (!$files->count()) return '';
            $this->session->offsetSet('file', $files->current()->getFilename());
        }

        return $this->session->offsetGet('file');
    }

    /**
     * @return GlobIterator
     */
    public function getFiles(): GlobIterator
    {
        return new GlobIterator(sprintf('%s/*.%s', $this->getPath(), $this->getExt()), FilesystemIterator::SKIP_DOTS & FilesystemIterator::CURRENT_AS_FILEINFO);
    }

    /**
     * @return string
     */
    public function getExt(): string
    {
        return $this->config->get('file')->get('ext');
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->config->get('file')->get('path');
    }

    /**
     * @param string $filename
     * @return string
     */
    public function getFilename(string $filename): string
    {
        return sprintf(
            '%s/%s.%s',
            $this->getPath(),
            str_replace('.' . $this->getExt(), '', basename($filename)),
            $this->getExt()
        );
    }

    /**
     * @param array|null $data
     * @return ViewModel
     */
    public function getView(array $data = []): ViewModel
    {
        return new ViewModel(array_merge([
            'collection' => $this->getCollection(),
            'namespace' => $this->getNamespace(),
            'title' => sprintf('%s %s', $this->getNamespace(), $this->params()->fromRoute('action')),
            'files' => $this->getFiles(),
            'file' => $this->getFile(),
        ], $data));
    }

    /**
     * @return string
     */
    protected function getFormAction(): string
    {
        return explode(',', $this->params()->fromPost('action'))[0];
    }

    /**
     * @return array
     */
    protected function getFormData(): array
    {
        $data = $this->params()->fromPost();
        if (array_key_exists('action', $data)) unset($data['action']);
        return $data;
    }

    /**
     * @return string
     */
    protected function getFormId(): string
    {
        return explode(',', $this->params()->fromPost('action'))[1];
    }

    /**
     * @return string
     */
    protected function getFormParam(): string
    {
        return explode(',', $this->params()->fromPost('action'))[2];
    }

    /**
     * @return string
     */
    protected function getNamespace(): string
    {
        return strtolower(explode('\\', get_called_class())[0]);
    }

    /**
     * @return bool
     */
    protected function isPost(): bool
    {
        return $this->request->isPost();
    }
}