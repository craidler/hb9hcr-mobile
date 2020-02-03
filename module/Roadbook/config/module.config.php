<?php
namespace Roadbook;

use HB9HCR\Service\Map\Google;
use Roadbook\Factory\MapsFactory;
use Roadbook\Factory\ControllerFactory;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'roadbook' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/roadbook[/:action]',
                    'defaults' => [
                        'controller' => Controller\FileController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'route' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/route',
                    'defaults' => [
                        'controller' => Controller\RoadbookController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'waypoint' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/waypoint[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\WaypointController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\FileController::class => ControllerFactory::class,
            Controller\RoadbookController::class => ControllerFactory::class,
            Controller\WaypointController::class => ControllerFactory::class,
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
            'MapService' => Google::class,
        ],
    ],
    Module::class => [
        'data' => __DIR__ . '/../../../public/data',
        'path' => __DIR__ . '/../../../public/data/roadbook',
        'extension' => 'json',
        'prefix' => basename(__NAMESPACE__),
    ],
];
