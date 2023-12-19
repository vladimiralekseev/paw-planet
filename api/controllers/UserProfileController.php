<?php

namespace api\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\web\IdentityInterface;

class UserProfileController extends AccessController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'index' => ['get'],
                    ],
                ],
            ]
        );
    }
    /**
     * @OA\Get(
     *     path="/user-profile/",
     *     security={{"bearerAuth":{}}},
     *     tags={"User"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                     "username": "username2",
     *                     "email": "email2222@email.com",
     *                     "statusCode": 10,
     *                     "status": "Active",
     *                     "created_at": "2023-12-19 21:22:44"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function actionIndex(): IdentityInterface
    {
        return Yii::$app->user->identity;
    }
}
