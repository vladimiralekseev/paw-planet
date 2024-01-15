<?php

namespace common\models;

use yii\db\ActiveQuery;

class PetImages extends _source_PetImages
{
    /**
     * @return bool
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

    public function fields(): array
    {
        $arr = array_merge(
            parent::fields(),
            [
                'img' => static function($model) {
                    return $model->img ? $model->img->url : null;
                },
                'small_img' => static function($model) {
                    return $model->smallImg ? $model->smallImg->url : null;
                },
                'middle_img' => static function($model) {
                    return $model->middleImg ? $model->middleImg->url : null;
                },
                'is_main' => static function($model) {
                    return $model->pet->img_id === $model->img_id;
                }
            ]
        );
        unset($arr['img_id'], $arr['middle_img_id'], $arr['small_img_id']);
        return $arr;
    }

    /**
     * Gets query for [[Img]].
     *
     * @return ActiveQuery
     */
    public function getImg(): ActiveQuery
    {
        return $this->hasOne(Files::class, ['id' => 'img_id']);
    }

    /**
     * Gets query for [[MiddleImg]].
     *
     * @return ActiveQuery
     */
    public function getMiddleImg(): ActiveQuery
    {
        return $this->hasOne(Files::class, ['id' => 'middle_img_id']);
    }

    /**
     * Gets query for [[MiddleImg]].
     *
     * @return ActiveQuery
     */
    public function getSmallImg(): ActiveQuery
    {
        return $this->hasOne(Files::class, ['id' => 'small_img_id']);
    }
}
