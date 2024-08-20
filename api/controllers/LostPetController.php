<?php

namespace api\controllers;

use api\models\forms\LostPetForm;
use api\models\forms\LostPetImageForm;
use api\models\forms\LostPetListForm;
use common\models\LostPet;
use common\models\SiteUser;
use Throwable;
use Yii;
use yii\data\Pagination;
use yii\db\StaleObjectException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class LostPetController extends AccessPremiumController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'my-list'      => ['get'],
                        'create'       => ['post'],
                        'update'       => ['put'],
                        'image-upload' => ['post'],
                        'list'         => ['get'],
                        'detail'       => ['get'],
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
     *                              "pageSize": "20"
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
    public function actionMyList(): array
    {
        $query = LostPet::find()->where(['user_id' => Yii::$app->user->identity->id]);
        $itemCount = $query->count();
        $pagination = new Pagination(['totalCount' => $itemCount, 'pageSize' => 20]);

        return [
            'pagination' => [
                'totalCount' => $itemCount,
                'page'       => $pagination->page + 1,
                'pageSize'   => $pagination->pageSize,
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
     * @throws ForbiddenHttpException
     */
    public function actionCreate(): array
    {
        /** @var SiteUser $user */
        $user = Yii::$app->user->identity;
        $user->refresh();
        if (!$user->accessToCreateMoreLostPets()) {
            throw new ForbiddenHttpException(
                'To create more than 3 Pets in a month you should have Premium Plus subscription.',
                self::ACCESS_CODE_SUBSCRIPTION_IS_NOT_ENOUGH
            );
        }
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
     *     tags={"Lost Pet"},
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
     * @param $id
     *
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

    /**
     * Upload an image
     *
     * @OA\Post(
     *     path="/lost-pet/{id}/image-upload/",
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
     *          name="id",
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"file"},
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
     *
     * @param $id
     *
     * @return array
     * @throws BadRequestHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionImageUpload($id): array
    {
        $petImageForm = new LostPetImageForm(['lost_pet_id' => $id]);

        if ($petImageForm->save()) {
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
     * Lost Pet list
     *
     * @OA\Get(
     *     path="/lost-pet/list/",
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
     *     @OA\Parameter(
     *          name="nickname",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="distance",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="lat",
     *          in="query",
     *          @OA\Schema(
     *              type="float",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="lng",
     *          in="query",
     *          @OA\Schema(
     *              type="float",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          description="breed_ids, example: 1,14,22",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          name="breed_ids",
     *          in="query",
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          description="Color ids, example: 1,14,22",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          name="color_ids",
     *          in="query",
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *         description="Type (lost or found)",
     *          name="type",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *         description="Date yyyy-mm-dd (e.g.: 2024-01-18 will be search from 2024-01-15 to 2024-01-21)",
     *          name="date",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="age_from",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *          name="age_to",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Pet list",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                          "pagination": {
     *                              "pageSize": "20"
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
     * @throws BadRequestHttpException
     */
    public function actionList(): array
    {
        $petListForm = new LostPetListForm();
        $petListForm->load(Yii::$app->request->get());

        if ($petListForm->validate()) {
            $query = $petListForm->getQuery();
            $itemCount = $query->count();
            $pagination = new Pagination(['totalCount' => $itemCount, 'pageSize' => 20]);

            return [
                'pagination' => [
                    'totalCount' => $itemCount,
                    'page'       => $pagination->page + 1,
                    'pageSize'   => $pagination->pageSize,
                ],
                'pets'       => $query->offset($pagination->offset)->limit($pagination->limit)->all()
            ];
        }

        if ($errors = $petListForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Lost Pet details
     *
     * @OA\Get(
     *     path="/lost-pet/{id}/detail/",
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
     *          name="id",
     *          in="path",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Pet details",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "id": 1,
     *                         "nickname": "nickname",
     *                      }
     *                 )
     *             )
     *         }
     *     )
     * )
     *
     * @param $id
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionDetail($id)
    {
        $pet = LostPet::find()->where(['id' => $id, 'status' => LostPet::STATUS_ACTIVE])->one();

        if (!$pet) {
            throw new NotFoundHttpException('Pet not found.');
        }

        return $pet;
    }
}
