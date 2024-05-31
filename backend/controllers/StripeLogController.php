<?php

namespace backend\controllers;

use backend\models\search\StripeLogSearch;
use common\models\StripeLog;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class StripeLogController extends CrudController
{
    public $modelClass = StripeLog::class;
    public $modelSearchClass = StripeLogSearch::class;

    /**
     * @return string|void|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        throw new NotFoundHttpException('Page not found.');
    }

    /**
     * @param $id
     *
     * @return string|void|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        throw new NotFoundHttpException('Page not found.');
    }
}
