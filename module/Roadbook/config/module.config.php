<?php
namespace Roadbook;

use HB9HCR\Service\Map\Google;
use Roadbook\Factory\MapsFactory;
use Roadbook\Factory\ControllerFactory;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'roadbook' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/roadbook[/:action]',
                    'defaults' => [
                        'controller' => Controller\RoadbookController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\RoadbookController::class => ControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            Google::class => MapsFactory::class,
        ],
        'aliases' => [
            'GoogleMaps' => Google::class,
        ],
    ],
];
