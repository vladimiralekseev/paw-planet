<?php

namespace api\controllers;

use api\models\forms\LostPetForm;
use common\models\LostPet;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

class LostPetController extends AccessController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'list'          => ['get'],
                        'create'        => ['post'],
                        'update'        => ['put'],
                    ],
                ],
            ]
        );
    }

    /**
     * My lost pet list
     *
     * @OA\Get(
     *     path="/my-lost-pet/list/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Lost Pet"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Response",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                          "pagination": {
     *                              "totalCount": "1"
     *                           },
     *                          "pets": {
     *                              {
     *                                  "id": 1,
     *                                  "nickname": "nickname",
     *                              }
     *                          }
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
        $query = LostPet::find()->where(['user_id' => Yii::$app->user->identity->id]);
        $itemCount = $query->count();
        $pagination = new Pagination(['totalCount' => $itemCount, 'pageSize' => 20]);

        return [
            'pagination' => [
                'totalCount' => $itemCount,
                'page'       => $pagination->page + 1,
                'pageCount'  => $pagination->pageCount,
            ],
            'pets'       => $query->offset($pagination->offset)->limit($pagination->limit)->all()
        ];
    }

    /**
     * Create a lost pet
     *
     * @OA\Post(
     *     path="/lost-pet/create/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Lost Pet"},
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
     *                 required={"color_ids","latitude","longitude","country","state","city","address",
     *     "when", "type"},
     *                 @OA\Property(
     *                     property="nickname",
     *                     type="string",
     *                     maxLength=128,
     *                 ),
     *                 @OA\Property(
     *                     property="breed_id",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="color_ids",
     *                     type="string",
     *                     maxLength=128,
     *                 ),
     *                 @OA\Property(
     *                     description="Months count",
     *                     property="age",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="latitude",
     *                     type="number",
     *                 ),
     *                 @OA\Property(
     *                     property="longitude",
     *                     type="number",
     *                 ),
     *                 @OA\Property(
     *                     property="country",
     *                     type="string",
     *                     maxLength=64,
     *                 ),
     *                 @OA\Property(
     *                     property="state",
     *                     type="string",
     *                     maxLength=64,
     *                 ),
     *                 @OA\Property(
     *                     property="city",
     *                     type="string",
     *                     maxLength=64,
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     type="string",
     *                     maxLength=128,
     *                 ),
     *                 @OA\Property(
     *                     description="Type (lost, found)",
     *                     property="type",
     *                     type="string",
     *                     maxLength=8,
     *                 ),
     *                 @OA\Property(
     *                     property="when",
     *                     type="string",
     *                     maxLength=16,
     *                 ),
     *                 example={
     *                      "nickname": "nickname",
     *                      "breed_id": 1,
     *                      "color_ids": "2,5",
     *                      "age": 2,
     *                      "latitude": "50.4450105000000",
     *                      "longitude": "30.4188569000000",
     *                      "country": "USA",
     *                      "state": "Missouri",
     *                      "city": "Branson",
     *                      "address": "123 Street",
     *                      "type": "lost",
     *                      "when": "2024-01-22",
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
     *                         "message": "Your profile is updated!",
     *                         "pet": {
     *                              "id": 1,
     *                          },
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
        $petForm = new LostPetForm();
        if ($petForm->load(Yii::$app->request->post()) && $petForm->save()) {
            return $this->successResponse(
                'Pet is created!',
                LostPet::find()->where(['id' => $petForm->pet_id])->one(),
                'pet'
            );
        }
        if ($errors = $petForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Update a lost pet
     *
     * @OA\Put(
     *     path="/lost-pet/{id}/update/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet"},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
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
     *                 required={"nickname", "breed_id"},
     *                 @OA\Property(
     *                     property="nickname",
     *                     type="string",
     *                     maxLength=128,
     *                 ),
     *                 @OA\Property(
     *                     property="description",
     *                     type="string",
     *                     maxLength=1024,
     *                 ),
     *                 @OA\Property(
     *                     property="needs",
     *                     type="string",
     *                     maxLength=1024,
     *                 ),
     *                 @OA\Property(
     *                     property="good_with",
     *                     type="string",
     *                     maxLength=1024,
     *                 ),
     *                 @OA\Property(
     *                     property="breed_id",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="age",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     description="Days of week, example: 1,3,6",
     *                     property="available",
     *                     type="integer",
     *                 ),
     *                 example={
     *                      "nickname": "nickname",
     *                      "description": "description",
     *                      "needs": "needs",
     *                      "good_with": "good_with",
     *                      "breed_id": 1,
     *                      "age": 2,
     *                      "available": "1,3,7",
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
     *                         "message": "Your profile is updated!",
     *                         "pet": {
     *                              "id": 1,
     *                          },
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionUpdate($id): array
    {
        $petForm = new LostPetForm(['pet_id' => $id]);
        if ($petForm->load(Yii::$app->request->post()) && $petForm->save()) {
            return $this->successResponse(
                'Pet is updated!',
                LostPet::find()->where(['id' => $petForm->pet_id])->one(),
                'pet'
            );
        }
        if ($errors = $petForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }
}
