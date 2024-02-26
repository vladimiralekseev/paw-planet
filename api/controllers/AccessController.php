<?php

namespace api\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;

class AccessController extends BaseController
{
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        $auth = $behaviors['authenticator'];
        unset($behaviors['authenticator']);

        if (Yii::$app->params['corsOrigin']) {
            $behaviors['corsFilter'] = [
                'class' => Cors::class,
                'cors'  => [
                    'Origin'                        => Yii::$app->params['corsOrigin'],
                    'Access-Control-Request-Method' => ['*'],
                    'Access-Control-Allow-Headers'  => ['*'],
                    'Access-Control-Expose-Headers' => ['*']
                ],
            ];
        }

        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];
        return $behaviors;
    }
}
