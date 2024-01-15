<?php

use api\components\CustomErrorHandler;
use common\models\SiteUser;
use yii\web\JsonResponseFormatter;
use yii\web\JsonParser;
use common\models\User;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'components' => [
//        'request' => [
//            'csrfParam' => '_csrf-frontend',
//        ],
        'request' => [
            'parsers' => [
                'application/json' => JsonParser::class,
            ]
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => JsonResponseFormatter::class,
                    'prettyPrint' => YII_DEBUG,
                ],
            ],
//            'on beforeSend' => function ($event) {
////                $response = $event->sender;
////                if (in_array($response->statusCode, [301, 302])) {
////                    return;
////                }
////                if (!$response->isSuccessful) {
////                    $response->data = \yii\helpers\ArrayHelper::merge($response->data, [
////                        'result' => 'Error',
////                        'statusCode' => $response->statusCode,
////                    ]);
////
////                    if (empty($response->data['message'])) {
////                        $response->data['message'] = $response->statusText;
////                    }
////                }
////                $response->statusCode = 200;
//            },
        ],
        'user' => [
            'identityClass' => SiteUser::class,
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
//            'enableAutoLogin' => true,
//            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
//        'session' => [
//            // this is the name of the session cookie used for login on the frontend
//            'name' => 'advanced-frontend',
//        ],
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
//            'class' => CustomErrorHandler::class,
//            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'suffix'          => '/',
            'rules'           => [
//                [
//                    'class' => 'yii\rest\UrlRule',
//                    'controller' => ['v1/country','v1/user','v1/site'],
//                    'tokens' => [
//                        '{id}' => ''
//                    ]
//                ]
                '/'                                             => 'site/index',
                '/logout'                                       => 'site/logout',
                '/pet/update/<id:[\d]+>/'                       => 'pet/update',
                '/pet-images/list/<pet_id:[\d]+>/'              => 'pet-images-list/list',
                '/pet-images/delete/<pet_image_id:[\d]+>/'      => 'pet-images/delete',
                '/pet-images/set-as-main/<pet_image_id:[\d]+>/' => 'pet-images/set-as-main',
                '/pet/breeds/'                                  => 'breed/index',
            ],
        ],
    ],
    'params' => $params,
];
