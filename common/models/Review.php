<?php

namespace common\models;

use Throwable;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\StaleObjectException;

class Review extends _source_Review
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

    /**
     * @return bool
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function beforeDelete(): bool
    {
        if (!empty($this->petImg)) {
            $this->petImg->delete();
        }
        if (!empty($this->userImg)) {
            $this->userImg->delete();
        }
        return parent::beforeDelete();
    }

    public function fields(): array
    {
        $arr = array_merge(
            parent::fields(),
            [
                'user_img' => static function ($model) {
                    return $model->userImg ? $model->userImg->url : null;
                },
                'pet_img'  => static function ($model) {
                    return $model->petImg ? $model->petImg->url : null;
                },
            ]
        );
        unset($arr['user_img_id'], $arr['pet_img_id']);
        return $arr;
    }
}
