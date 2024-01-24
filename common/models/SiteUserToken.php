<?php

namespace common\models;

use DateInterval;
use DateTime;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class SiteUserToken extends _source_SiteUserToken
{
    public const EXPIRED_TIME = 24;

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class'      => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value'      => new Expression('NOW()'),
            ],
        ];
    }

    public function fields(): array
    {
        return [
            'token',
            'expired_at',
            'created_at',
            'expires_in' => static function() {
                return self::EXPIRED_TIME;
            },
        ];
    }

    public static function generate($user): SiteUserToken
    {
        self::deleteAll(['<', 'expired_at', (new DateTime())->format('Y-m-d h:i:s')]);
        $token = new self(
            [
                'site_user_id' => $user->id,
                'token'        => bin2hex(random_bytes(64)),
                'expired_at'   => (new DateTime())->add(new DateInterval('PT' . self::EXPIRED_TIME . 'H'))
                    ->format('Y-m-d h:i:s'),
            ]
        );
        $token->save();
        /** @var SiteUserToken $token */
        $token = self::find()->where(['id' => $token->id])->one(); // need to fix this because "created_at"
        return $token;
    }
}
