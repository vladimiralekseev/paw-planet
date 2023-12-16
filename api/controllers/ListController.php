<?php

namespace api\controllers;

use Yii;

class ListController extends BaseController
{
    public function actionIndex()
    {
        return Yii::$app->user->identity;
    }
}
