<?php
namespace Roadbook;

use HB9HCR\Service\Map\Google;
use Roadbook\Factory\GoogleMapsFactory;
use Roadbook\Factory\RouteControllerFactory;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'route' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/roadbook/route[/:action]',
                    'defaults' => [
                        'controller' => Controller\RouteController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\RoadbookController::class => InvokableFactory::class,
            Controller\RouteController::class => RouteControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'service_manager' => [
        'factories' => [
            Google::class => GoogleMapsFactory::class,
        ],
        'aliases' => [
            'GoogleMaps' => Google::class,
        ],
    ],
];
