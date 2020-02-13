<?php
namespace Roadbook;

use Laminas\ServiceManager\ServiceManager;
use Roadbook\Factory;
use Laminas\Router\Http\Segment;
use Roadbook\Helper\Maps;
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
    'view_helpers' => [
        'aliases' => [
            'maps' => Maps::class,
        ],
        'factories' => [
            Maps::class => function (ServiceManager $serviceManager) {
                return (new Maps())->setService($serviceManager->get(GoogleMaps::class));
            },
        ],
    ],
    'service_manager' => [
        'factories' => [
            GoogleMaps::class => Factory\ServiceFactory::class,
        ],
    ],
    Module::class => [
        'file' => [
            'path' => __DIR__ . '/../../../public/data/roadbook',
            'ext' => 'json',
        ],
        'api_key' => file_exists(__DIR__ . '/api.key') ? file_get_contents(__DIR__ . '/api.key') : '',
        'api_url' => 'https://maps.googleapis.com/maps/api',
    ],
];
