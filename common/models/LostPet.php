<?php

namespace common\models;

use Throwable;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\StaleObjectException;

class LostPet extends _source_LostPet
{
    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function beforeDelete(): bool
    {
        if (!empty($this->img)) {
            $this->img->delete();
        }
        if (!empty($this->smallImg)) {
            $this->smallImg->delete();
        }
        if (!empty($this->middleImg)) {
            $this->middleImg->delete();
        }
        return parent::beforeDelete();
    }

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
                'colors' => static function(LostPet $model) {
                    return $model->petColors;
                },
            ]
        );
        unset($arr['breed_id'], $arr['user_id'], $arr['img_id'], $arr['middle_img_id'], $arr['small_img_id']);
        return $arr;
    }
}
