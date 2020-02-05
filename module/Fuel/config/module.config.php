<?php
namespace Fuel;

use Laminas\Router\Http\Segment;
use Fuel\Factory\ControllerFactory;

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
        'path' => __DIR__ . '/../../../public/data/fuel',
        'prefix' => 'Fuel',
        'extension' => 'json',
    ],
];
