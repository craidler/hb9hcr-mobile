<?php
namespace Roadbook;

use Laminas\Config\Config;
use Laminas\ServiceManager\ServiceManager;
use Roadbook\Controller\MapController;
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
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\MapController::class => function (ServiceManager $serviceManager) {
                $controller = new MapController;
                $controller->setMaps($serviceManager->get(GoogleMaps::class));
                return $controller;
            },
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
            GoogleMaps::class => function (ServiceManager $serviceManager) {
                $service = new GoogleMaps();
                $service->setConfig((new Config($serviceManager->get('config')))->get(GoogleMaps::class));
                return $service;
            },
        ],
    ],
    Module::class => [
        'file' => [
            'path' => __DIR__ . '/../data/roadbook',
            'ext' => 'json',
        ],
    ],
    GoogleMaps::class => [
        'data' => __DIR__ . '/../data',
        'key' => file_exists(__DIR__ . '/api.key') ? file_get_contents(__DIR__ . '/api.key') : '',
        'url' => 'https://maps.googleapis.com/maps/api',
    ],
];
