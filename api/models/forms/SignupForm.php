<?php

namespace api\models\forms;

use Yii;
use yii\base\Model;
use common\models\SiteUser;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $last_name;
    public $first_name;

    public function formName(): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['email'], 'trim'],
            [['email', 'password', 'first_name'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['first_name', 'last_name'], 'string', 'max' => 128],
            [['first_name', 'last_name'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'], //xss protection
            ['email', 'unique', 'targetClass' => SiteUser::class, 'message' => 'This email address has already been taken.'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup(): ?bool
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new SiteUser();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        return $user->save() && $this->sendEmail($user);
    }

    /**
     * Sends confirmation email to user
     * @param SiteUser $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail(SiteUser $user): bool
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
