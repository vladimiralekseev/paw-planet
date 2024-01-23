<?php

use webvimark\modules\UserManagement\UserManagementModule;
use yii\caching\FileCache;
use phtamas\yii2\imageprocessor\Component;

return [
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache'          => [
            'class' => FileCache::class,
        ],
        'imageProcessor' => [
            'class'          => Component::class,
            'jpegQuality'    => 85,
            'pngCompression' => 9,
            'define'         => [
                'profilePreview'      => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 190, 'height' => 210, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 190, 'height' => 210, 'x' => 'center - 95', 'y' => 'center - 105'],
                    ],
                ],
                'profileSmallPreview' => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 60, 'height' => 60, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 60, 'height' => 60, 'x' => 'center - 95', 'y' => 'center - 105'],
                    ],
                ],
                'petImage' => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 400, 'height' => 450, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 400, 'height' => 450, 'x' => 'center - 200', 'y' => 'center - 225'],
                    ],
                ],
                'petImageMiddle' => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 160, 'height' => 160, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 160, 'height' => 160, 'x' => 'center - 80', 'y' => 'center - 80'],
                    ],
                ],
                'petImageSmall' => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 80, 'height' => 80, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 80, 'height' => 80, 'x' => 'center - 40', 'y' => 'center - 40'],
                    ],
                ],
                'reviewUser' => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 80, 'height' => 80, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 80, 'height' => 80, 'x' => 'center - 40', 'y' => 'center - 40'],
                    ],
                ],
                'reviewPet' => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 40, 'height' => 40, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 40, 'height' => 40, 'x' => 'center - 20', 'y' => 'center - 20'],
                    ],
                ],
                'lostPetImage' => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 1200, 'height' => 800, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 1200, 'height' => 800, 'x' => 'center - 600', 'y' => 'center - 400'],
                    ],
                ],
                'lostPetImageMiddle' => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 160, 'height' => 160, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 160, 'height' => 160, 'x' => 'center - 80', 'y' => 'center - 80'],
                    ],
                ],
                'lostPetImageSmall' => [
                    'process' => [
                        ['autorotate'],
                        ['resize', 'width' => 80, 'height' => 80, 'scaleTo' => 'cover'],
                        ['crop', 'width' => 80, 'height' => 80, 'x' => 'center - 40', 'y' => 'center - 40'],
                    ],
                ],
            ],
        ],
    ],
    'modules'    => [
        'user-management' => [
            'class' => UserManagementModule::class,
        ],
    ],
];
