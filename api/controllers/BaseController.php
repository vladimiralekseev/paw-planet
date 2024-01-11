<?php

namespace api\controllers;

use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\web\Response;

class BaseController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

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
