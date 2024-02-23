<?php

namespace api\controllers;

use api\models\forms\ResponseLostPetForm;
use api\models\forms\ResponseLostPetStatusForm;
use common\models\ResponseLostPet;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

class ResponseController extends AccessController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'to-me'   => ['get'],
                        'from-me' => ['get'],
                        'create'  => ['post'],
                        'status'  => ['patch'],
                    ],
                ],
            ]
        );
    }

    /**
     * Responses to me
     *
     * @OA\Get(
     *     path="/response/to-me/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Responses"},
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
     *         description="Response",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                        {"id":1, "lost_pet": {"...":"..."}, "status":"new", "request_owner": {"...":"..."}},
     *                        {"id":1, "lost_pet": {"...":"..."}, "status":"approved", "request_owner": {"...":"..."}}
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     *
     * @return array
     */
    public function actionToMe(): array
    {
        return ResponseLostPet::find()->where(
            [
                'user_id' => Yii::$app->user->identity->id,
            ]
        )
            ->innerJoinWith(
                [
                    'lostPet' => static function ($query) {
                        $query->innerJoinWith(['user'])->with(['img', 'smallImg', 'middleImg', 'breed']);
                    }
                ]
            )
            ->all();
    }

    /**
     * Responses from me
     *
     * @OA\Get(
     *     path="/response/from-me/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Responses"},
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
     *         description="Response",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                        {"id":1, "lost_pet": {"...":"..."}, "status":"new", "request_owner": {"...":"..."}},
     *                        {"id":1, "lost_pet": {"...":"..."}, "status":"approved", "request_owner": {"...":"..."}}
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     *
     * @return array
     */
    public function actionFromMe(): array
    {
        return ResponseLostPet::find()->where(
            [
                'request_owner_id' => Yii::$app->user->identity->id,
            ]
        )
            ->innerJoinWith(['pet'])
            ->all();
    }

    /**
     * Create a response
     *
     * @OA\Post(
     *     path="/response/create/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Responses"},
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
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"lost_pet_id"},
     *                 @OA\Property(
     *                     property="lost_pet_id",
     *                     type="integer",
     *                 ),
     *                 example={
     *                      "lost_pet_id": 1,
     *                  }
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
     *                         "message": "Response has created!",
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCreate(): array
    {
        $responseLostPetForm = new ResponseLostPetForm();
        if ($responseLostPetForm->load(Yii::$app->request->post()) && $responseLostPetForm->save()) {
            return $this->successResponse(
                'Response has created!'
            );
        }
        if ($errors = $responseLostPetForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Change a response status
     *
     * @OA\Patch(
     *     path="/response/{id}/status/{status}/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Responses"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          description="Status (approved, rejected, canceled)",
     *          name="status",
     *          in="path",
     *          required=true,
     *          @OA\Schema(
     *              type="string",
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
     *                         "message": "Response has updated!",
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @param $id
     * @param $status
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionStatus($id, $status): array
    {
        $responseLostPetStatusForm = new ResponseLostPetStatusForm();
        $responseLostPetStatusForm->id = $id;
        $responseLostPetStatusForm->status = $status;
        if ($responseLostPetStatusForm->save()) {
            return $this->successResponse(
                'Response has updated!'
            );
        }
        if ($errors = $responseLostPetStatusForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }
}
