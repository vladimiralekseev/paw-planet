<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Pet extends _source_Pet
{
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value'      => new Expression('NOW()'),
            ],
        ];
    }

    public function fields(): array
    {
        $arr = array_merge(
            parent::fields(),
            [
                'breed' => static function($model) {
                    return $model->breed;
                },
                'for_borrow' => static function($model) {
                    return (bool)$model->for_borrow;
                },
                'for_walk' => static function($model) {
                    return (bool)$model->for_walk;
                },
                'img' => static function($model) {
                    return $model->img ? $model->img->url : null;
                },
                'small_img' => static function($model) {
                    return $model->smallImg ? $model->smallImg->url : null;
                },
                'middle_img' => static function($model) {
                    return $model->middleImg ? $model->middleImg->url : null;
                },
                'user' => static function($model) {
                    return SiteUserPublic::find()->where(['id' => $model->user_id])->one();
                },
            ]
        );
        unset($arr['breed_id'], $arr['user_id'], $arr['img_id'], $arr['middle_img_id'], $arr['small_img_id']);
        return $arr;
    }
}
