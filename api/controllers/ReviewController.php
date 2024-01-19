<?php

namespace api\controllers;

use common\models\Review;
use yii\filters\VerbFilter;

class ReviewController extends BaseController
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
     * Review list. To manage reviews go to https://admin.paw-planet.gointeractive.com.ua/review/index
     *
     * @OA\Get(
     *     path="/review/list/",
     *     tags={"Reviews"},
     *     @OA\Response(
     *         response="200",
     *         description="Review list",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                          "id": 2,
     *                          "name": "Nameddddd",
     *                          "description": "Description Description Description Description",
     *                          "short_description": "Short Descriptionsadf",
     *                          "date": "2024-01-18 14:50:00",
     *                          "created_at": "2024-01-18 14:53:11",
     *                          "updated_at": "2024-01-18 15:09:48",
     *                          "user_img": "//api.paw-planet.local/upload/review-user/73/1bc9a8104975bd2c.jpg",
     *                          "pet_img": "//api.paw-planet.local/upload/review-pet/61/f2f07245c9a9af63.jpg"
     *                      }
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
        return Review::find()->all();
    }
}
