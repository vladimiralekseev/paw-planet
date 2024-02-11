<?php

namespace api\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        if (Yii::$app->params['corsOrigin']) {
            $behaviors['corsFilter'] = [
                'class' => Cors::class,
                'cors'  => [
                    'Origin' => Yii::$app->params['corsOrigin'],
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
            'name' => 'Success',
            'status' => 200,
            'code' => 0,
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
