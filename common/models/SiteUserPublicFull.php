<?php

namespace common\models;

use Yii;

/**
 * User model
 */
class SiteUserPublicFull extends SiteUser
{
    public function fields(): array
    {
        $petIds = array_map(
            static function ($pet) {
                return $pet->id;
            },
            $this->pets
        );
        $requestAccess = Yii::$app->user->identity ? UserRequestPet::find()->where(
            [
                'pet_id'           => $petIds,
                'status'           => UserRequestPet::STATUS_APPROVED,
                'request_owner_id' => Yii::$app->user->identity->id,
            ]
        )->one() : null;
        return [
            'id',
            'last_name',
            'first_name',
            'about',
            'country',
            'state',
            'city',
            'status',
            'status_name'     => static function ($model) {
                return self::getStatusValue($model->status);
            },
            'img'             => static function ($model) {
                return $model->img ? $model->img->url : null;
            },
            'small_img'       => static function ($model) {
                return $model->smallImg ? $model->smallImg->url : null;
            },
            'updated_at',
            'created_at',
            'security_fields' => static function ($model) use ($requestAccess) {
                return $requestAccess ? [
                    'phone_number',
                    'email',
                    'my_location',
                    'latitude',
                    'longitude',
                    'address',
                    'whats_app',
                    'facebook',
                ] : null;
            },
        ];
    }
}
