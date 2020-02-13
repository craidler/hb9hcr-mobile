<?php
namespace Application\Controller;

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
            return $this->redirect()->refresh();
        }

        return $this->getView()->setTemplate(sprintf('%1$s/%1$s/form', $this->getNamespace()));
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
                    break;

                case 'delete':
                    $filename = $this->getFilename($this->getFormId());
                    if ($filename == $this->getFile()) $this->session->offsetUnset('file');
                    unlink($filename);
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
        if (!$this->collection) $this->collection = Collection::load($this->getFile(), $this->getClass());
        return $this->collection;
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
        $collection = $this->getCollection();

        try {
            $item = $collection->find($this->params()->fromRoute('id'));
        }
        catch (Exception $e) {
            $item = null;
        }

        return new ViewModel(array_merge([
            'collection' => $collection,
            'namespace' => $this->getNamespace(),
            'files' => $this->getFiles(),
            'item' => $item,
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
        return explode('\\', get_called_class())[0];
    }

    /**
     * @return bool
     */
    protected function isPost(): bool
    {
        return $this->request->isPost();
    }
}