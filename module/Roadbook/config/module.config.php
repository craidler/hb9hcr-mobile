<?php
namespace Roadbook;

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
            Controller\RouteController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
