<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $name
 * @property string $stripe_product_id
 * @property int $status
 * @property string $type
 * @property int $period
 * @property int $amount
 * @property string|null $trial_days
 *
 * @property SiteUser[] $siteUsers
 */
class _source_Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'stripe_product_id', 'status', 'type', 'period'], 'required'],
            [['status', 'period', 'amount'], 'integer'],
            [['name'], 'string', 'max' => 128],
            [['stripe_product_id', 'type', 'trial_days'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'stripe_product_id' => 'Stripe Product ID',
            'status' => 'Status',
            'type' => 'Type',
            'period' => 'Period',
            'amount' => 'Amount',
            'trial_days' => 'Trial Days',
        ];
    }

    /**
     * Gets query for [[SiteUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSiteUsers()
    {
        return $this->hasMany(SiteUser::class, ['product_id' => 'id']);
    }
}
