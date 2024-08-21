<?php

namespace common\models;

class Product extends _source_Product
{
    public const STATUS_ACTIVE   = 1;
    public const STATUS_INACTIVE = 0;

    public const TYPE_PLAN_PREMIUM      = 'premium';
    public const TYPE_PLAN_PREMIUM_PLUS = 'premium-plus';

    public const PERIOD_1  = 1;
    public const PERIOD_3  = 3;
    public const PERIOD_6  = 6;
    public const PERIOD_12 = 12;

    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                [
                    ['status', 'type'],
                    'unique',
                    'targetAttribute' => [1 => 'status', 'type', 'period'],
                    'message'         => 'Only one Product with current Period and Type can be an active. To activate this Product deactivate another one.',
                    'when' => static function ($model) {
                        return (bool) $model->status;
                    }
                ],
            ]
        );
    }

    public function fields(): array
    {
        return [
            'id',
            'name',
            'stripe_product_id',
            'status',
            'status_name' => static function (Product $model) {
                return self::getStatusValue($model->status);
            },
            'type',
            'period',
            'amount',
            'trial_days',
        ];
    }

    public function attributeLabels(): array
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'amount' => 'Amount (in cents)',
            ]
        );
    }

    public static function getStatusList(): array
    {
        return [
            self::STATUS_ACTIVE   => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
        ];
    }

    public static function getStatusValue($val): string
    {
        $ar = self::getStatusList();

        return $ar[$val] ?? $val;
    }

    public static function getTypeList(): array
    {
        return [
            self::TYPE_PLAN_PREMIUM      => 'Premium',
            self::TYPE_PLAN_PREMIUM_PLUS => 'Premium plus',
        ];
    }

    public static function getTypeValue($val): string
    {
        $ar = self::getTypeList();

        return $ar[$val] ?? $val;
    }

    public static function getPeriodList(): array
    {
        return [
            self::PERIOD_1  => '1 month',
            self::PERIOD_3  => '3 month',
            self::PERIOD_6  => '6 month',
            self::PERIOD_12 => '12 month',
        ];
    }

    public static function getPeriodValue($val): string
    {
        $ar = self::getPeriodList();

        return $ar[$val] ?? $val;
    }
}
