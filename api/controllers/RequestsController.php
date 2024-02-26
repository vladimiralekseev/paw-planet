<?php

namespace api\controllers;

use api\models\forms\UserRequestPetForm;
use api\models\forms\UserRequestPetStatusForm;
use common\models\UserRequestPet;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

class RequestsController extends AccessController
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
     * Requests to me
     *
     * @OA\Get(
     *     path="/requests/to-me/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Requests"},
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
     *                              {"id":1, "pet": {"...":"..."}, "type":"borrow", "status":"new",
     *                              "request_owner": {"...":"..."}},
     *                              {"id":1, "pet": {"...":"..."}, "type":"walk", "status":"approved",
     *                              "request_owner": {"...":"..."}}
     *                      }
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
        return UserRequestPet::find()->where(
            [
                'user_id' => Yii::$app->user->identity->id,
            ]
        )
            ->innerJoinWith(
                [
                    'pet' => static function ($query) {
                        $query->innerJoinWith(['user'])->with(['img', 'smallImg', 'middleImg', 'breed']);
                    }
                ]
            )
            ->all();
    }

    /**
     * Requests from me
     *
     * @OA\Get(
     *     path="/requests/from-me/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Requests"},
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
     *                              {"id":1, "pet": {"...":"..."}, "type":"borrow", "status":"new",
     *                              "request_owner": {"...":"..."}},
     *                              {"id":1, "pet": {"...":"..."}, "type":"walk", "status":"approved",
     *                              "request_owner": {"...":"..."}}
     *                      }
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
        return UserRequestPet::find()->where(
            [
                'request_owner_id' => Yii::$app->user->identity->id,
            ]
        )
            ->innerJoinWith(['pet'])
            ->all();
    }

    /**
     * Create a request
     *
     * @OA\Post(
     *     path="/requests/create/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Requests"},
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
     *                 required={"pet_id", "type"},
     *                 @OA\Property(
     *                     property="pet_id",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     description="Type (walk, borrow)",
     *                     property="type",
     *                     type="string"
     *                 ),
     *                 example={
     *                      "pet_id": 1,
     *                      "type": "walk",
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
     *                         "message": "Request has created!",
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
        $userRequestPetForm = new UserRequestPetForm();
        if ($userRequestPetForm->load(Yii::$app->request->post()) && $userRequestPetForm->save()) {
            return $this->successResponse(
                'Request has created!'
            );
        }
        if ($errors = $userRequestPetForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Change a request status
     *
     * @OA\Patch(
     *     path="/requests/{id}/status/{status}/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Requests"},
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
     *                         "message": "Request has updated!",
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
        $userRequestPetStatusForm = new UserRequestPetStatusForm();
        $userRequestPetStatusForm->id = $id;
        $userRequestPetStatusForm->status = $status;
        if ($userRequestPetStatusForm->save()) {
            return $this->successResponse(
                'Request has updated!'
            );
        }
        if ($errors = $userRequestPetStatusForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }
}
