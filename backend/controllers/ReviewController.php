<?php

namespace backend\controllers;

use backend\models\search\ReviewSearch;
use common\models\Review;
use common\models\upload\ReviewPetUploadForm;
use common\models\upload\ReviewUserUploadForm;
use Throwable;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ReviewController extends CrudController
{
    use UploadFileTrait;

    public $modelClass = Review::class;
    public $modelSearchClass = ReviewSearch::class;

    /**
     * @return string|Response
     */
    public function actionCreate()
    {
        return $this->reviewSave(new Review(), 'create');
    }

    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Throwable
     */
    public function actionUpdate($id)
    {
        /**
         * @var Review $model
         */
        $model = Review::find()->where(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException('Page not found.');
        }

        return $this->reviewSave($model, 'update');
    }

    /**
     * @param Review $model
     * @param string $view
     *
     * @return string|Response
     */
    private function reviewSave(Review $model, string $view)
    {
        $reviewPetUploadForm = new ReviewPetUploadForm();
        $reviewUserUploadForm = new ReviewUserUploadForm();

        if ($model->load(Yii::$app->request->post())) {
            $this->uploadFile($reviewPetUploadForm, $model, 'pet_img');
            $this->uploadFile($reviewUserUploadForm, $model, 'user_img');

            if ($model->save() && empty($reviewPetUploadForm->errors) && empty($reviewUserUploadForm->errors)) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->renderIsAjax(
            $view,
            compact(
                'model',
                'reviewPetUploadForm',
                'reviewUserUploadForm',
            )
        );
    }
}
