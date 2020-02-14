<?php
namespace Fuel;

use Application\Factory\ControllerFactory;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'fuel' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/fuel[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\FuelController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\FuelController::class => ControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    Module::class => [
        'file' => [
            'path' => __DIR__ . '/../data',
            'ext' => 'json',
        ],
    ],
];
