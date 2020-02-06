<?php
namespace Roadbook;

use Roadbook\Factory;
use Laminas\Router\Http\Segment;
use Roadbook\Service\GoogleMaps;

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
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\RoadbookController::class => Factory\ControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            GoogleMaps::class => Factory\ServiceFactory::class,
        ],
    ],
    Module::class => [
        'path' => __DIR__ . '/../../../public/data',
        'extension' => 'json',
        'key' => file_exists(__DIR__ . '/maps.key') ? file_get_contents(__DIR__ . '/maps.key') : '',
    ],
];
