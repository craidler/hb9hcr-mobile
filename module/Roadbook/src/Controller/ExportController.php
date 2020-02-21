<?php
namespace Roadbook\Controller;

use Application\Controller\FileController;
use Application\Model\Collection;
use DOMAttr;
use DOMDocument;
use DOMElement;
use DOMNode;
use Roadbook\Model\Waypoint;

/**
 * Class ExportController
 * @package Roadbook\Controller
 */
class ExportController extends FileController
{
    /**
     * @inheritdoc
     */
    protected $class = Waypoint::class;

    public function waypointAction()
    {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $root = $xml->appendChild(new DOMElement('wpts'));

        foreach ($this->getCollection() as $item) {
            $waypoint = $root->appendChild(new DOMElement('wpt'));
            $waypoint->setAttribute('lat', $item->latitude);
            $waypoint->setAttribute('lon', $item->longitude);
            $waypoint->appendChild(new DOMElement('name', $item->name));
            $waypoint->appendChild(new DOMElement('sym', 'Camping'));
            $waypoint->appendChild(new DOMElement('category', 'Campground'));
            $root->appendChild($waypoint);
        }

        $content = $xml->saveXML();

        $response = $this->getResponse();
        $response->setContent($content);
        $headers = $response->getHeaders();
        $headers->addHeaderLine('Content-type', 'text/xml');
        return $response;
    }
}