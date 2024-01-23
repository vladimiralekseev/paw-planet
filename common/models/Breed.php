<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class Breed extends _source_Breed
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

    public function rules(): array
    {
        return array_merge(parent::rules(), [
            [['name'], 'unique'],
        ]);
    }
}
