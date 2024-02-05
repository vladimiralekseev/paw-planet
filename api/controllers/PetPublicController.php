<?php

namespace api\controllers;

use api\models\forms\PetListForm;
use common\models\Pet;
use common\models\PetDetail;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class PetPublicController extends BaseController
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
     * Pet list
     *
     * @OA\Get(
     *     path="/pet/list/",
     *     tags={"Pet"},
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
     *     tags={"Pet"},
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
