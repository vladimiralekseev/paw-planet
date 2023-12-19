<?php

namespace api\controllers;

use Yii;
use yii\web\IdentityInterface;

class UserProfileController extends AccessController
{
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
     *     )
     * )
     */
    public function actionIndex(): IdentityInterface
    {
        return Yii::$app->user->identity;
    }
}
