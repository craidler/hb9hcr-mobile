<?php
namespace Diary;

use Application\Factory\ControllerFactory;
use Laminas\Router\Http\Segment;
use Diary\Controller;

return [
    'router' => [
        'routes' => [
            'diary' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/diary[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\DiaryController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'diary/file' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/diary/file',
                    'defaults' => [
                        'controller' => Controller\DiaryController::class,
                        'action' => 'file',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\DiaryController::class => ControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    Module::class => [
        'path' => __DIR__ . '/../../../public/data/diary',
        'extension' => 'json',
    ],
];
