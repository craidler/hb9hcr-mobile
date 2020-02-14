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
        'strategies' => [
            'ViewJsonStrategy',
        ]
    ],
    Module::class => [
        'file' => [
            'path' => __DIR__ . '/../data',
            'ext' => 'dat',
        ],
        'nmea' => [
            'interval' => 1,
            'device' => '/dev/ttyACM0',
            'types' => ['GGA', 'VTG'],
            'log' => '%s/%s.dat',
        ],
        'check' => [
            'gpsd' => 'ps -ef | grep -v grep | grep -c gpsd',
            'gpsl' => 'ps -ef | grep -v grep | grep -c gpsl',
        ],
    ],
];
