<?php

namespace api\models\forms;

use common\models\SiteUser;
use common\models\SiteUserToken;
use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
//    public $rememberMe = true;

    private $_user;

    public function formName(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
//            // rememberMe must be a boolean value
//            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        /** @var SiteUser $user */
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return array|null
     */
    public function getToken(): ?SiteUserToken
    {
        if ($this->validate()) {
            return $this->getUser() ? $this->getUser()->generateAuthToken() : null;
        }

        return null;
    }

    /**
     * Finds user by [[username]]
     *
     * @return SiteUser|null
     */
    protected function getUser(): ?SiteUser
    {
        if ($this->_user === null) {
            $this->_user = SiteUser::findByUsername($this->username);
        }

        return $this->_user;
    }
}
