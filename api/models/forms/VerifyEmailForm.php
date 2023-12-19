<?php

namespace api\models\forms;

use common\models\SiteUser;
use yii\base\InvalidArgumentException;
use yii\base\Model;

class VerifyEmailForm extends Model
{
    /**
     * @var string
     */
    public $token;

    /**
     * @var SiteUser $_user
     */
    private $_user;


    /**
     * Creates a form model with given token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws InvalidArgumentException if token is empty or not valid
     */
    public function __construct($token, array $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidArgumentException('Verify email token cannot be blank');
        }
        $this->_user = SiteUser::findByVerificationToken($token);
        if (!$this->_user) {
            throw new InvalidArgumentException('Wrong verify token');
        }
        parent::__construct($config);
    }

    /**
     * Verify email
     *
     * @return SiteUser|null the saved model or null if saving fails
     */
    public function verifyEmail(): ?SiteUser
    {
        $user = $this->_user;
        $user->status = SiteUser::STATUS_ACTIVE;
        return $user->save(false) ? $user : null;
    }
}
