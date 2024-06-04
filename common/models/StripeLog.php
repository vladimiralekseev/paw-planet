<?php

namespace common\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

class StripeLog extends _source_StripeLog
{
    public const TYPE_CUSTOMER_SUBSCRIPTION_CREATED = 'customer.subscription.created';
    public const TYPE_CUSTOMER_SUBSCRIPTION_DELETED = 'customer.subscription.deleted';
    public const TYPE_CUSTOMER_SUBSCRIPTION_UPDATED = 'customer.subscription.updated';

    public const SUBSCRIPTION_STATUS_ACTIVE = 'active';
    public const SUBSCRIPTION_STATUS_TRIALING = 'trialing';
    public const SUBSCRIPTION_STATUS_CANCELED = 'canceled';

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
}
