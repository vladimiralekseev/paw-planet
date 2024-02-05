<?php

namespace backend\controllers;

use backend\models\search\PetSearch;
use common\models\Pet;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PetController extends CrudController
{
    use UploadFileTrait;

    public $modelClass = Pet::class;
    public $modelSearchClass = PetSearch::class;

    /**
     * @return string|void|Response
     * @throws NotFoundHttpException
     */
    public function actionCreate()
    {
        throw new NotFoundHttpException('Page not found.');
    }
}
