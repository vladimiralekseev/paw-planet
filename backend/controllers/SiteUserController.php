<?php

namespace backend\controllers;

use backend\models\search\SiteUserSearch;
use common\models\SiteUser;
use common\models\UserRequestPet;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteUserController extends CrudController
{
    public $modelClass = SiteUser::class;
    public $modelSearchClass = SiteUserSearch::class;

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
        /** @var SiteUser $model */
        $model = $this->findModel($id);

        $dataProviderRequestFrom = $model ? new ActiveDataProvider(
            [
                'query' => UserRequestPet::find()->where(['request_owner_id' => $model->id]),
                'sort'  => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ],
                ],
            ]
        ) : null;

        $dataProviderRequestTo = $model ? new ActiveDataProvider(
            [
                'query' => UserRequestPet::find()
                    ->innerJoinWith(['pet'])
                    ->where(['user_id' => $model->id]),
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
                'model'                   => $this->findModel($id),
                'dataProviderRequestFrom' => $dataProviderRequestFrom,
                'dataProviderRequestTo'   => $dataProviderRequestTo,
            ]
        );
    }
}
