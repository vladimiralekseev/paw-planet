<?php

namespace backend\controllers;

use backend\models\search\LostPetSearch;
use common\models\LostPet;
use common\models\ResponseLostPet;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class LostPetController extends CrudController
{
    public $modelClass = LostPet::class;
    public $modelSearchClass = LostPetSearch::class;

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
        /** @var LostPet $model */
        $model = $this->findModel($id);

        $dataProviderResponse = $model ? new ActiveDataProvider(
            [
                'query' => ResponseLostPet::find()->where(['lost_pet_id' => $model->id]),
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
                'model'                => $this->findModel($id),
                'dataProviderResponse' => $dataProviderResponse,
            ]
        );
    }
}
