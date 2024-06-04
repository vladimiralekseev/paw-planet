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
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property string|null $verification_token
 * @property string|null $phone_number
 * @property string|null $about
 * @property string|null $my_location
 * @property float|null $latitude
 * @property float|null $longitude
 * @property string|null $whats_app
 * @property string|null $facebook
 * @property int|null $img_id
 * @property int|null $small_img_id
 * @property string|null $country
 * @property string|null $state
 * @property string|null $city
 * @property string|null $address
 * @property string|null $stripe_customer_id
 * @property int|null $product_id
 * @property string|null $product_expired_date
 * @property int|null $stripe_trial_is_used
 * @property string|null $subscription_status
 *
 * @property Files $img
 * @property LostPet[] $lostPets
 * @property Pet[] $pets
 * @property Product $product
 * @property ResponseLostPet[] $responseLostPets
 * @property SiteUserToken[] $siteUserTokens
 * @property Files $smallImg
 * @property StripeLog[] $stripeLogs
 * @property UserRequestPet[] $userRequestPets
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
            [['username', 'first_name', 'auth_key', 'password_hash', 'email'], 'required'],
            [['status', 'img_id', 'small_img_id', 'product_id', 'stripe_trial_is_used'], 'integer'],
            [['created_at', 'updated_at', 'product_expired_date'], 'safe'],
            [['about', 'my_location'], 'string'],
            [['latitude', 'longitude'], 'number'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'verification_token'], 'string', 'max' => 255],
            [['first_name', 'last_name', 'address'], 'string', 'max' => 128],
            [['auth_key'], 'string', 'max' => 32],
            [['phone_number', 'country', 'state', 'city', 'stripe_customer_id'], 'string', 'max' => 64],
            [['whats_app', 'facebook'], 'string', 'max' => 256],
            [['subscription_status'], 'string', 'max' => 16],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['img_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['img_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::class, 'targetAttribute' => ['product_id' => 'id']],
            [['small_img_id'], 'exist', 'skipOnError' => true, 'targetClass' => Files::class, 'targetAttribute' => ['small_img_id' => 'id']],
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
            'img_id' => 'Img ID',
            'small_img_id' => 'Small Img ID',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'address' => 'Address',
            'stripe_customer_id' => 'Stripe Customer ID',
            'product_id' => 'Product ID',
            'product_expired_date' => 'Product Expired Date',
            'stripe_trial_is_used' => 'Stripe Trial Is Used',
            'subscription_status' => 'Subscription Status',
        ];
    }

    /**
     * Gets query for [[Img]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getImg()
    {
        return $this->hasOne(Files::class, ['id' => 'img_id']);
    }

    /**
     * Gets query for [[LostPets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLostPets()
    {
        return $this->hasMany(LostPet::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Pets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPets()
    {
        return $this->hasMany(Pet::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    /**
     * Gets query for [[ResponseLostPets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getResponseLostPets()
    {
        return $this->hasMany(ResponseLostPet::class, ['request_owner_id' => 'id']);
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

    /**
     * Gets query for [[SmallImg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSmallImg()
    {
        return $this->hasOne(Files::class, ['id' => 'small_img_id']);
    }

    /**
     * Gets query for [[StripeLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStripeLogs()
    {
        return $this->hasMany(StripeLog::class, ['site_user_id' => 'id']);
    }

    /**
     * Gets query for [[UserRequestPets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserRequestPets()
    {
        return $this->hasMany(UserRequestPet::class, ['request_owner_id' => 'id']);
    }
}
