<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class ResponseLostPet extends _source_ResponseLostPet
{
    public const STATUS_NEW     = 'new';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED  = 'rejected';
    public const STATUS_CANCELED  = 'canceled';

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
                'lost_pet' => 'lostPet',
                'request_owner' => static function($model) {
                    return SiteUserPublic::find()->where(['id' => $model->request_owner_id])->one();
                },
            ]
        );
        unset($arr['lost_pet_id'], $arr['request_owner_id']);
        return $arr;
    }
}
