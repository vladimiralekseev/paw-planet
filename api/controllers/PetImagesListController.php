<?php

namespace api\controllers;

use common\models\Pet;
use yii\web\NotFoundHttpException;

class PetImagesListController extends BaseController
{
    /**
     * Pet Images list
     *
     * @OA\Get(
     *     path="/pet-images/list/{pet_id}/",
     *     tags={"Pet Images"},
     *     @OA\Parameter(
     *          name="pet_id",
     *          in="path",
     *          @OA\Schema(
     *              required={"pet_id"},
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
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
     * @param $pet_id
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionList($pet_id): array
    {
        /** @var Pet $pet */
        $pet = Pet::find()->where(['id' => $pet_id])->one();
        if (!$pet) {
            throw new NotFoundHttpException('Pet not found.');
        }
        return $pet->petImages;
    }
}
