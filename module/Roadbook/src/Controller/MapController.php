<?php
namespace Roadbook\Controller;

use Laminas\Http\Headers;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Stdlib\ResponseInterface;
use Roadbook\Service\GoogleMaps;

/**
 * Class RoadbookController
 * @package Roadbook\Controller
 */
class MapController extends AbstractActionController
{
    /**
     * @var GoogleMaps
     */
    protected $maps;

    /**
     * @param GoogleMaps $maps
     * @return MapController
     */
    public function setMaps(GoogleMaps $maps): MapController
    {
        $this->maps = $maps;
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function imageAction()
    {
        list($position, $zoom, $type) = explode(':', base64_decode($this->params()->fromRoute('base64')));
        list($latitude, $longitude) = explode(',', $position);

        $content = $this->maps->getImage($latitude, $longitude, $zoom, $type);
        $response = $this->getResponse();
        $response->setContent($content);

        /**
         * @var Headers $headers
         */
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Transfer-Encoding', 'binary');
        $headers->addHeaderLine('Content-Type', 'image/png');
        $headers->addHeaderLine('Content-Length', strlen($content));

        return $response;
    }

    /**
     * @return ResponseInterface
     */
    public function routeAction()
    {
        list($origin, $destination) = explode(':', base64_decode($this->params()->fromRoute('base64')));

        $content = $this->maps->getRoute($origin, $destination);
        $response = $this->getResponse();
        $response->setContent($content);

        /**
         * @var Headers $headers
         */
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-Transfer-Encoding', 'text');
        $headers->addHeaderLine('Content-Type', 'text/json');
        $headers->addHeaderLine('Content-Length', strlen($content));

        return $response;
    }
}
