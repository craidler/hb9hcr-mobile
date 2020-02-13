<?php
namespace Application\Controller;

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
     * @var Config
     */
    protected $config;

    /**
     * @var Container
     */
    protected $session;

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
                    break;
            }

            return $this->redirect()->refresh();
        }

        $view = $this->getView();
        $view->setTemplate('application/file/index');
        return $view;
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
     * @return bool
     */
    protected function isPost(): bool
    {
        return $this->request->isPost();
    }
}