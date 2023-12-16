<?php

namespace api\controllers;

use api\models\forms\LoginForm;
use api\models\forms\SignupForm;
use api\models\forms\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;

class AuthController extends BaseController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'signup' => ['post'],
                    ],
                ],
            ]
        );
    }

    /**
     * @return string[]
     * @throws BadRequestHttpException
     */
    public function actionSignup(): array
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            return $this->successResponse(
                'Thank you for registration. Please check your inbox for verification email.'
            );
        }
        if ($errors = $model->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Verify email address
     *
     * @param string $token
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if (($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            return $this->successResponse(
                'Your email has been confirmed!'
            );
        }
        if ($errors = $model->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Sorry, we are unable to verify your account with provided token.');
    }

    /**
     * @return array|Response
     * @throws BadRequestHttpException
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $token = $model->getToken()) {
            return $token;
        }

        $model->password = '';

        if ($errors = $model->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }
}
