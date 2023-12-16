<?php

namespace api\controllers;

use Yii;

class ProfileController extends AccessController
{
    public function actionIndex()
    {
        return Yii::$app->user->identity;
    }
}
