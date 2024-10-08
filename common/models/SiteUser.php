<?php

namespace common\models;

use DateInterval;
use DateTime;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property string $fullName
 */
class SiteUser extends _source_SiteUser implements IdentityInterface
{
    public const STATUS_DELETED  = 0;
    public const STATUS_INACTIVE = 9;
    public const STATUS_ACTIVE   = 10;

    public const SUBSCRIBE_STATUS_ACTIVE   = 'active';
    public const SUBSCRIBE_STATUS_INACTIVE = 'inactive';

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

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                ['status', 'default', 'value' => self::STATUS_INACTIVE],
                ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
//                [
//                    'product_expired_date',
//                    'datetime',
//                    'format'  => 'php:Y-m-d H:i:s',
//                    'message' => 'The format of Product Expired Date is invalid. Example: 2024-12-30 14:55:00'
//                ],
//                [
//                    'product_id',
//                    'required',
//                    'when'    => function (SiteUser $model) {
//                        return !empty($model->product_expired_date);
//                    },
//                    'message' => 'Product is required if Product Expired Date is set',
//                ],
//                [
//                    'product_expired_date',
//                    'required',
//                    'when'    => function (SiteUser $model) {
//                        return !empty($model->product_id);
//                    }, 'whenClient' => "function (attribute, value) {
//                        return $('#siteuser-product_id').val() !== '';
//                    }",
//                    'message' => 'Product Expired Date is required if Product is set',
//                ],
            ]
        );
    }

    public function fields(): array
    {
        return [
            'id',
            'last_name',
            'first_name',
            'phone_number',
            'about',
            'email',
            'my_location',
            'latitude',
            'longitude',
            'country',
            'state',
            'city',
            'address',
            'whats_app',
            'facebook',
            'status',
            'status_name'         => static function (SiteUser $model) {
                return self::getStatusValue($model->status);
            },
            'img'                 => static function (SiteUser $model) {
                return $model->img->url ?? null;
            },
            'small_img'           => static function (SiteUser $model) {
                return $model->smallImg->url ?? null;
            },
            'subscription_active' => static function (SiteUser $model) {
                return $model->subscription_status === self::SUBSCRIBE_STATUS_ACTIVE;
            },
            'subscription_type'   => static function (SiteUser $model) {
                return $model->product ?? null;
            },
            'updated_at',
            'created_at',
        ];
    }

    public static function getStatusList(): array
    {
        return [
            self::STATUS_ACTIVE   => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DELETED  => 'Deleted',
        ];
    }

    public static function getStatusValue($val): string
    {
        $ar = self::getStatusList();

        return $ar[$val] ?? $val;
    }

    public static function getSubscriptionStatusList(): array
    {
        return [
            self::SUBSCRIBE_STATUS_ACTIVE   => 'Active',
            self::SUBSCRIBE_STATUS_INACTIVE => 'Inactive',
        ];
    }

    public static function getSubscriptionStatusValue($val): string
    {
        $ar = self::getSubscriptionStatusList();

        return $ar[$val] ?? $val;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(
            [
                'token'  => $token,
                'status' => self::STATUS_ACTIVE
            ]
        )->joinWith('siteUserTokens')->one();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username): ?SiteUser
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByEmail($email): ?SiteUser
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token): ?SiteUser
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne(
            [
                'password_reset_token' => $token,
                'status'               => self::STATUS_ACTIVE,
            ]
        );
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     *
     * @return static|null
     */
    public static function findByVerificationToken($token): ?SiteUser
    {
        return static::findOne(
            [
                'verification_token' => $token,
                'status'             => self::STATUS_INACTIVE
            ]
        );
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey): ?bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function generateAuthToken(): SiteUserToken
    {
        return SiteUserToken::generate($this);
    }

    public function getFullName(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * @return Customer
     * @throws ApiErrorException
     */
    public function stripeUpdate(): Customer
    {
        return (new Stripe())->saveCustomer($this);
    }

    /**
     * @param Product $product
     *
     * @return Session
     * @throws ApiErrorException
     */
    public function generateCheckoutSessions(Product $product): Session
    {
        return (new Stripe())->generateCheckoutSessions($this, $product);
    }

    public function accessToCreateMorePets(): bool
    {
        $count = $this->getPets()
            ->where(
                [
                    '>',
                    'created_at',
                    (new DateTime())->sub(new DateInterval('P30D'))->format('Y-m-d')
                ]
            )
            ->count();
        $count2 = $this->getLostPets()
            ->where(
                [
                    '>',
                    'created_at',
                    (new DateTime())->sub(new DateInterval('P30D'))->format('Y-m-d')
                ]
            )
            ->count();
        return $this->product && $this->subscription_status === self::SUBSCRIBE_STATUS_ACTIVE &&
            (
                $this->product->type === Product::TYPE_PLAN_PREMIUM_PLUS ||
                ($this->product->type === Product::TYPE_PLAN_PREMIUM && ($count + $count2 < 3))
            );
    }

    public function accessToCreateMoreLostPets(): bool
    {
        return $this->accessToCreateMorePets();
    }
}
