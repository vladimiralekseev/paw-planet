<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "site_user".
 *
 * @property int $id
 * @property string $username
 * @property string $first_name
 * @property string|null $last_name
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $password_reset_token
 * @property string $email
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $verification_token
 * @property string|null $phone_number
 * @property string|null $about
 * @property string|null $my_location
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $whats_app
 * @property string|null $facebook
 *
 * @property SiteUserToken[] $siteUserTokens
 */
class _source_SiteUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'first_name', 'auth_key', 'password_hash', 'email', 'created_at', 'updated_at'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['about', 'my_location'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            [['auth_key'], 'string', 'max' => 32],
            [['phone_number'], 'string', 'max' => 64],
            [['whats_app', 'facebook'], 'string', 'max' => 256],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'verification_token' => 'Verification Token',
            'phone_number' => 'Phone Number',
            'about' => 'About',
            'my_location' => 'My Location',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'whats_app' => 'Whats App',
            'facebook' => 'Facebook',
        ];
    }

    /**
     * Gets query for [[SiteUserTokens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSiteUserTokens()
    {
        return $this->hasMany(SiteUserToken::class, ['site_user_id' => 'id']);
    }
}
