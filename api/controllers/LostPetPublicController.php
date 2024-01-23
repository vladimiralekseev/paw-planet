<?php

namespace api\controllers;

use api\models\forms\LostPetListForm;
use common\models\LostPet;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class LostPetPublicController extends BaseController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'list'   => ['get'],
                        'detail' => ['get'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lost Pet list
     *
     * @OA\Get(
     *     path="/lost-pet/list/",
     *     tags={"Lost Pet"},
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
     *     tags={"Lost Pet"},
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
        $pet = LostPet::find()->where(['id' => $id])->one();

        if (!$pet) {
            throw new NotFoundHttpException('Pet not found.');
        }

        return $pet;
    }
}
