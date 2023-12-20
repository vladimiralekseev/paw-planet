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
     *                     "id": 11,
     *                     "last_name": "lastname",
     *                     "first_name": "firstname",
     *                     "phone_number": null,
     *                     "about": null,
     *                     "email": "emaidddddl@email.com",
     *                     "my_location": null,
     *                     "latitude": null,
     *                     "longitude": null,
     *                     "whats_app": null,
     *                     "facebook": null,
     *                     "status": 10,
     *                     "status_name": "Active",
     *                     "updated_at": "2023-12-20 14:42:06",
     *                     "created_at": "2023-12-20 14:42:06"
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
