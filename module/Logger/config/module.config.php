<?php
namespace Logger;

use Application\Factory\ControllerFactory;
use Laminas\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'logger' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/logger[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\LoggerController::class,
                        'action' => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\LoggerController::class => ControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    Module::class => [
        'path' => __DIR__ . '/../../../public/data',
        'extension' => 'dat',
    ],
];