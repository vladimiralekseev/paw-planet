<?php

namespace backend\controllers;

use backend\models\search\ProductSearch;
use common\models\Pet;
use common\models\Product;
use common\models\UserRequestPet;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ProductController extends CrudController
{
    public $modelClass = Product::class;
    public $modelSearchClass = ProductSearch::class;

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
