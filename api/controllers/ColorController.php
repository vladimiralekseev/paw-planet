<?php

namespace api\controllers;

use common\models\Color;
use yii\filters\VerbFilter;

class ColorController extends BaseController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'list'   => ['get'],
                    ],
                ],
            ]
        );
    }

    /**
     * Colors list. To manage colors go to https://admin.paw-planet.gointeractive.com.ua/color/index
     *
     * @OA\Get(
     *     path="/colors/",
     *     tags={"Colors"},
     *     @OA\Response(
     *         response="200",
     *         description="Colors list",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={{
     *                          "id": 2,
     *                          "color": "Black",
     *                          "created_at": "2024-01-22 15:30:04",
     *                          "updated_at": "2024-01-22 15:32:16"
     *                      }}
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
        return Color::find()->all();
    }
}
