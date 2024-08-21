<?php

namespace api\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\HttpCache;

class BaseController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors[] = [
            'class' => HttpCache::class,
            'cacheControlHeader' => 'public, no-cache',
            'lastModified' => function ($action, $params) {
                return '1231231231';
            }
        ];

        if (Yii::$app->params['corsOrigin']) {
            $behaviors['corsFilter'] = [
                'class' => Cors::class,
                'cors'  => [
                    'Origin'                        => Yii::$app->params['corsOrigin'],
                    'Access-Control-Request-Method' => ['*'],
                    'Access-Control-Allow-Headers'  => ['*'],
                    'Access-Control-Expose-Headers' => ['*'],
                    'Access-Control-Max-Age' => 0,
                ],
            ];
        }

        $behaviors['contentNegotiator'] = [
            'class'   => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        return $behaviors;
    }

    public function successResponse($message = null, $data = null, $dataName = 'data'): array
    {
        $result = [
            'name'   => 'Success',
            'status' => 200,
            'code'   => 0,
        ];
        if ($message) {
            $result['message'] = $message;
        }
        if ($data) {
            $result[$dataName] = $data;
        }
        return $result;
    }
}
