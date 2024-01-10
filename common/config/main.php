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
            ],
        ],
    ],
    'modules'    => [
        'user-management' => [
            'class' => UserManagementModule::class,
        ],
    ],
];
