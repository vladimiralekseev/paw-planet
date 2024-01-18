<?php

namespace backend\controllers;

use common\models\Review;
use Yii;
use yii\helpers\Inflector;

trait UploadFileTrait
{
    protected function uploadFile($uploadForm, &$model, $field): void
    {
        /**
         * @var Review $model
         */

        $uploadForm->loadInstance();

        if ($uploadForm->validate() && $uploadForm->upload()) {
            $model->{$field . '_id'} = $uploadForm->id;
        } elseif (!empty(
            $model->{Inflector::variablize($field)}
            && !empty(Yii::$app->request->post(Inflector::variablize('delete_' . $field)))
        )
        ) {
            $model->{Inflector::variablize($field)}->delete();
        }
    }
}
