<?php

namespace api\controllers;

use api\models\forms\PetForm;
use api\models\forms\PetListForm;
use api\models\forms\PetUpdateFieldForm;
use common\models\Pet;
use common\models\PetDetail;
use common\models\SiteUser;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class PetController extends AccessPremiumController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'my-list'       => ['get'],
                        'create'        => ['post'],
                        'update'        => ['put'],
                        'update-walk'   => ['patch'],
                        'update-borrow' => ['patch'],
                        'list'          => ['get'],
                        'detail'        => ['get'],
                    ],
                ],
            ]
        );
    }

    /**
     * My pet list
     *
     * @OA\Get(
     *     path="/my-pet/list/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet"},
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
        $query = Pet::find()->where(['user_id' => Yii::$app->user->identity->id]);
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
     * Create a pet
     *
     * @OA\Post(
     *     path="/pet/create/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet"},
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
     *                     description="Months count",
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
     * @throws ForbiddenHttpException
     */
    public function actionCreate(): array
    {
        /** @var SiteUser $user */
        $user = Yii::$app->user->identity;
        if (!$user->accessToCreateMorePets()) {
            throw new ForbiddenHttpException(
                'To create more than 3 Pets in a month you should have Premium Plus subscription.',
                self::ACCESS_CODE_SUBSCRIPTION_IS_NOT_ENOUGH
            );
        }

        $petForm = new PetForm();
        if ($petForm->load(Yii::$app->request->post()) && $petForm->save()) {
            return $this->successResponse(
                'Pet is created!',
                Pet::find()->where(['id' => $petForm->pet_id])->one(),
                'pet'
            );
        }
        if ($errors = $petForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Update a pet
     *
     * @OA\Put(
     *     path="/pet/{id}/update/",
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
     *                         "message": "Pet is updated!",
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
        $petForm = new PetForm(['pet_id' => $id]);
        if ($petForm->load(Yii::$app->request->post()) && $petForm->save()) {
            return $this->successResponse(
                'Pet is updated!',
                Pet::find()->where(['id' => $petForm->pet_id])->one(),
                'pet'
            );
        }
        if ($errors = $petForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Update a pet walk status
     *
     * @OA\Patch(
     *     path="/pet/{id}/walk/{status}/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet"},
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
     *          description="Status (0 or 1)",
     *          name="status",
     *          in="path",
     *          required=true,
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
     *     @OA\Response(
     *         response="200",
     *         description="Response",
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
     *                         "message": "Updated!",
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
     * @param $status
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionUpdateWalk($id, $status): array
    {
        $petWalkForm = new PetUpdateFieldForm(
            [
                'pet_id' => $id,
                'status' => $status,
                'field'  => PetUpdateFieldForm::FIELD_WALK
            ]
        );
        if ($petWalkForm->save()) {
            return $this->successResponse(
                'Pet is updated!'
            );
        }
        if ($errors = $petWalkForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Update a pet borrow status
     *
     * @OA\Patch(
     *     path="/pet/{id}/borrow/{status}/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet"},
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
     *          description="Status (0 or 1)",
     *          name="status",
     *          in="path",
     *          required=true,
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
     *     @OA\Response(
     *         response="200",
     *         description="Response",
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
     *                         "message": "Updated!",
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
     * @param $status
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionUpdateBorrow($id, $status): array
    {
        $petWalkForm = new PetUpdateFieldForm(
            [
                'pet_id' => $id,
                'status' => $status,
                'field'  => PetUpdateFieldForm::FIELD_BORROW
            ]
        );
        if ($petWalkForm->save()) {
            return $this->successResponse(
                'Pet is updated!'
            );
        }
        if ($errors = $petWalkForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Pet list
     *
     * @OA\Get(
     *     path="/pet/list/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet"},
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
     *         description="For borrow (1 or 0)",
     *          name="for_borrow",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
     *          ),
     *          style="form"
     *     ),
     *     @OA\Parameter(
     *         description="For walk (1 or 0)",
     *          name="for_walk",
     *          in="query",
     *          @OA\Schema(
     *              type="integer",
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
     *     @OA\Parameter(
     *          description="Day of week (from 1 to 7), example: 1,5,7",
     *          name="available",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
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
        $petListForm = new PetListForm();
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
     * Pet details
     *
     * @OA\Get(
     *     path="/pet/{id}/detail/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Pet"},
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
        $pet = PetDetail::find()->where(['id' => $id, 'status' => Pet::STATUS_ACTIVE])->one();

        if (!$pet) {
            throw new NotFoundHttpException('Pet not found.');
        }

        return $pet;
    }
}
