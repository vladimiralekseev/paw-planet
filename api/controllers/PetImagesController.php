<?php

namespace api\controllers;

use api\models\forms\PetImageAsMainForm;
use api\models\forms\PetImageDeleteForm;
use api\models\forms\PetImageForm;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

class PetImagesController extends AccessController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'list'        => ['get'],
                        'upload'      => ['post'],
                        'update'      => ['put'],
                        'set-as-main' => ['patch'],
                        'delete'      => ['delete'],
                    ],
                ],
            ]
        );
    }

    /**
     * Upload an image
     *
     * @OA\Post(
     *     path="/pet-images/upload/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet Images"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"pet_id", "file"},
     *                 @OA\Property(
     *                     description="Pet Id",
     *                     property="pet_id",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     description="Set as main, 1 or 0",
     *                     property="set_as_main",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     description="file to upload",
     *                     property="file",
     *                     type="string",
     *                     format="binary",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="code",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string"
     *                     ),
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "Image is uploaded!"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionUpload(): array
    {
        $petImageForm = new PetImageForm();

        if ($petImageForm->load(Yii::$app->request->post()) && $petImageForm->save()) {
            return $this->successResponse(
                'Image is uploaded!'
            );
        }
        if ($errors = $petImageForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Set an image as a main image of a pet
     *
     * @OA\Patch(
     *     path="/pet-images/set-as-main/{pet_image_id}/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet Images"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\Parameter(
     *          name="pet_image_id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="code",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string"
     *                     ),
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "Image set as a main image!"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionSetAsMain($pet_image_id): array
    {
        $petImageForm = new PetImageAsMainForm();
        $petImageForm->pet_image_id = $pet_image_id;

        if ($petImageForm->save()) {
            return $this->successResponse(
                'Image set as a main image!'
            );
        }
        if ($errors = $petImageForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Delete an image
     *
     * @OA\Delete(
     *     path="/pet-images/delete/{pet_image_id}/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet Images"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\Parameter(
     *          name="pet_image_id",
     *          in="path",
     *          @OA\Schema(
     *              required={"pet_image_id"},
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="code",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string"
     *                     ),
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "Image has deleted!"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @return array
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($pet_image_id): array
    {
        $petImageDeleteForm = new PetImageDeleteForm(['pet_image_id' => $pet_image_id]);
        if ($petImageDeleteForm->delete()) {
            return $this->successResponse(
                'Image has deleted!'
            );
        }
        if ($errors = $petImageDeleteForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }
}
