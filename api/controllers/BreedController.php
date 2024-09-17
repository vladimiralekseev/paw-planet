<?php

namespace api\controllers;

use common\models\Breed;
use yii\filters\VerbFilter;

class BreedController extends BaseController
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
     * Breed list. To manage breeds go to https://admin.paw-planet.gointeractive.com.ua/breed/index
     *
     * @OA\Get(
     *     path="/breeds/",
     *     tags={"Breeds"},
     *     @OA\Response(
     *         response="200",
     *         description="Breeds list",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={{
     *                     "id": 11,
     *                     "name": "lastname",
     *                     "updated_at": "2023-12-20 14:42:06",
     *                     "created_at": "2023-12-20 14:42:06"
     *                     }}
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function actionList(): array
    {
        return Breed::find()->orderBy('name')->all();
    }
}
