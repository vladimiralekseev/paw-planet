<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stripe_log".
 *
 * @property int $id
 * @property int $site_user_id
 * @property string $event
 * @property string $data
 * @property string|null $created_at
 *
 * @property SiteUser $siteUser
 */
class _source_StripeLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stripe_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['site_user_id', 'event', 'data'], 'required'],
            [['site_user_id'], 'integer'],
            [['data'], 'string'],
            [['created_at'], 'safe'],
            [['event'], 'string', 'max' => 64],
            [['site_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => SiteUser::class, 'targetAttribute' => ['site_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'site_user_id' => 'Site User ID',
            'event' => 'Event',
            'data' => 'Data',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[SiteUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSiteUser()
    {
        return $this->hasOne(SiteUser::class, ['id' => 'site_user_id']);
    }
}
