<?php

namespace backend\controllers;

use backend\models\search\PetSearch;
use common\models\Pet;
use common\models\UserRequestPet;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PetController extends CrudController
{
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

    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        /** @var Pet $model */
        $model = $this->findModel($id);

        $dataProviderRequest = $model ? new ActiveDataProvider(
            [
                'query' => UserRequestPet::find()->where(['pet_id' => $model->id]),
                'sort'  => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
            ]
        ) : null;

        return $this->renderIsAjax(
            'view',
            [
                'model'               => $this->findModel($id),
                'dataProviderRequest' => $dataProviderRequest,
            ]
        );
    }
}
