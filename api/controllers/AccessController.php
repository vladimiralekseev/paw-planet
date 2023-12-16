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
        return $behaviors;
    }
}
