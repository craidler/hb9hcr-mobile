<?php
namespace Roadbook;

use Application\Factory\ControllerFactory;
use HB9HCR\Service\Map\Google;
use Roadbook\Factory\MapsFactory;
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
            'MapService' => Google::class,
        ],
    ],
    Module::class => [
        'path' => __DIR__ . '/../../../public/data/roadbook',
        'extension' => 'json',
    ],
];
