<?php

namespace common\models;

/**
 * User model
 */
class SiteUserPublic extends SiteUser
{
    public function fields(): array
    {
        return [
            'id',
            'last_name',
            'first_name',
            'about',
            'country',
            'state',
            'city',
            'status',
            'status_name' => static function ($model) {
                return self::getStatusValue($model->status);
            },
            'img'         => static function ($model) {
                return $model->img ? $model->img->url : null;
            },
            'small_img'   => static function ($model) {
                return $model->smallImg ? $model->smallImg->url : null;
            },
            'updated_at',
            'created_at',
        ];
    }
}
