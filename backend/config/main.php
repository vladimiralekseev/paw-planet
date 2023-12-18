<?php

use webvimark\modules\UserManagement\components\UserConfig;
use webvimark\modules\UserManagement\models\UserVisitLog;
use webvimark\modules\UserManagement\UserManagementModule;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules'             => [
        'user-management' => [
            'class'           => UserManagementModule::class,

            'on beforeAction' => static function (yii\base\ActionEvent $event) {
                if ($event->action->uniqueId === 'user-management/auth/login') {
                    $event->action->controller->layout = 'loginLayout.php';
                }
            },
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
//        'user' => [
//            'identityClass' => 'common\models\User',
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
//        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager'   => array(
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => array(
                ''      => 'site/index',
                'login' => 'site/login',
            ),
        ),
        'user'         => [
            'class'         => UserConfig::class,
            // Comment this if you don't want to record user logins
            'on afterLogin' => static function ($event) {
                UserVisitLog::newVisitor($event->identity->id);
            }
        ],
    ],
    'params' => $params,
];
