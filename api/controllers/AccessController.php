<?php

namespace api\controllers;

use yii\filters\auth\HttpBearerAuth;

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

        $behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];
        return $behaviors;
    }
}
