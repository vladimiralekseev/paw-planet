<?php

namespace api\controllers;

use common\models\Breed;

class BreedController extends BaseController
{
    /**
     * Breed list
     *
     * @OA\Get(
     *     path="/pet/breeds/",
     *     tags={"Pet"},
     *     @OA\Response(
     *         response="200",
     *         description="Breed list",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                     "id": 11,
     *                     "name": "lastname",
     *                     "updated_at": "2023-12-20 14:42:06",
     *                     "created_at": "2023-12-20 14:42:06"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     */
    public function actionIndex(): array
    {
        return Breed::find()->all();
    }
}
