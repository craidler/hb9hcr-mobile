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
                'type' => Segment::class,
                'options' => [
                    'route' => '/roadbook[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\RoadbookController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'roadbook/file' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/roadbook/file',
                    'defaults' => [
                        'controller' => Controller\FileController::class,
                        'action' => 'index',
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
        'key' => file_exists(__DIR__ . '/api.key') ? file_get_contents(__DIR__ . '/api.key') : '',
        'data' => __DIR__ . '/../../../public/data',
        'path' => __DIR__ . '/../../../public/data/roadbook',
        'extension' => 'json',
        'prefix' => basename(__NAMESPACE__),
    ],
];
