<?php

namespace api\controllers;

use common\models\Product;
use yii\filters\VerbFilter;

class ProductController extends BaseController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'list' => ['get'],
                    ],
                ],
            ]
        );
    }

    /**
     * Products list
     *
     * @OA\Get(
     *     path="/product/list/",
     *     tags={"Products"},
     *     @OA\Response(
     *         response="200",
     *         description="Products list",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         {
     *                             "id": 1,
     *                             "name": "name",
     *                             "stripe_product_id": "prod_QBnxrUA5vBFMtD",
     *                             "type": "premium",
     *                             "status": 1,
     *                             "period": 12,
     *                             "amount": 18000,
     *                         }
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     *
     * @return array
     */
    public function actionList(): array
    {
        return Product::find()->where(['status' => Product::STATUS_ACTIVE])->all();
    }
}
