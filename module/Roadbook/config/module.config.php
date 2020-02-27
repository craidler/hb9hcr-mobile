<?php
namespace Roadbook;

use Application\Factory\ControllerFactory;
use Laminas\Config\Config;
use Laminas\ServiceManager\ServiceManager;
use Laminas\Router\Http\Segment;
use Roadbook\Helper\Maps;
use Roadbook\Model\Waypoint;
use Roadbook\Service\GoogleMaps;

return [
    'router' => [
        'routes' => [
            'export' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/export[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\ExportController::class,
                        'action' => 'index',
                    ],
                ],
            ],
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
            'map-image' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/map/image/:base64',
                    'defaults' => [
                        'controller' => Controller\MapController::class,
                        'action' => 'image',
                    ],
                ],
            ],
            'map-route' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/map/route/:base64',
                    'defaults' => [
                        'controller' => Controller\MapController::class,
                        'action' => 'route',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\RoadbookController::class => ControllerFactory::class,
            Controller\ExportController::class => ControllerFactory::class,
            Controller\MapController::class => ControllerFactory::class,
        ],
    ],
    'controller_plugins' => [
        'factories' => [
            Plugin\Maps::class => function (ServiceManager $serviceManager) {
                return (new Plugin\Maps)->setService($serviceManager->get(GoogleMaps::class));
            }
        ],
        'aliases' => [
            'maps' => Plugin\Maps::class,
            'map' => Plugin\Maps::class,
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            GoogleMaps::class => function (ServiceManager $serviceManager) {
                return (new GoogleMaps())->setConfig((new Config($serviceManager->get('config')))->get(GoogleMaps::class));
            },
        ],
    ],
    Module::class => [
        'file' => [
            'path' => __DIR__ . '/../data/roadbook',
            'ext' => 'json',
        ],
        'calculation' => [
            'skip' => [Waypoint::TYPE_SUPPLY],
        ],
        'export' => [
            'path' => __DIR__ . '/../../../public/export',
        ],
    ],
    GoogleMaps::class => [
        'data' => __DIR__ . '/../data',
        'key' => file_exists(__DIR__ . '/api.key') ? file_get_contents(__DIR__ . '/api.key') : '',
        'url' => 'https://maps.googleapis.com/maps/api',
    ],
];
