<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_user_token".
 *
 * @property int $id
 * @property int $site_user_id
 * @property string $token
 * @property string|null $created_at
 * @property string|null $expired_at
 *
 * @property SiteUser $siteUser
 */
class _source_SiteUserToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_user_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['site_user_id', 'token'], 'required'],
            [['site_user_id'], 'integer'],
            [['created_at', 'expired_at'], 'safe'],
            [['token'], 'string', 'max' => 256],
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
            'token' => 'Token',
            'created_at' => 'Created At',
            'expired_at' => 'Expired At',
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
