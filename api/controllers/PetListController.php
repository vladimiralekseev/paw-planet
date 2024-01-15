<?php

namespace api\controllers;

use api\models\forms\PetListForm;
use Yii;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

class PetListController extends BaseController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'list' => ['get'],
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
     *         description="Breed list",
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
                    'pageCount'  => $pagination->pageCount,
                ],
                'pets'       => $query->offset($pagination->offset)->limit($pagination->limit)->all()
            ];
        }

        if ($errors = $petListForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }
}
