<?php

namespace api\controllers;

use api\models\forms\LoginForm;
use api\models\forms\PasswordResetRequestForm;
use api\models\forms\ResendVerificationEmailForm;
use api\models\forms\ResetPasswordForm;
use api\models\forms\SignupForm;
use api\models\forms\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;

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
                        'signup'                    => ['post'],
                        'login'                     => ['post'],
                        'verify-email'              => ['get'],
                        'request-password-reset'    => ['post'],
                        'reset-password'            => ['post'],
                        'resend-verification-email' => ['post'],
                    ],
                ],
            ]
        );
    }

    /**
     * Create a user.
     *
     * @OA\Post(
     *     path="/auth/signup/",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="first_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"first_name": "firstname", "last_name": "lastname", "email": "email@email.com",
     *     "password": "987asdfi7i87"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="name",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="status",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="code",
     *                         type="integer"
     *                     ),
     *                     @OA\Property(
     *                         property="message",
     *                         type="string"
     *                     ),
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "Thank you for registration. Please check your inbox for verification email."
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
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
     * Resend verification email.
     *
     * @OA\Post(
     *     path="/auth/resend-verification-email/",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 example={"email": "email@site.com"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "Check your email for further instructions."
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     *
     * @return array|string
     * @throws BadRequestHttpException
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return $this->successResponse(
                    'Check your email for further instructions.'
                );
            }

            throw new BadRequestHttpException(
                'Sorry, we are unable to resend verification email for the provided email address.'
            );
        }
        if ($errors = $model->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Verify a email.
     *
     * @param string $token
     *
     * @return Response
     */
    public function actionVerifyEmail($token): Response
    {
        $model = null;
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            return $this->redirect('https://' . Yii::$app->params['domainRoot'] . '/?email_validation=false');
        }
        if ($model && ($user = $model->verifyEmail()) && Yii::$app->user->login($user)) {
            return $this->redirect('https://' . Yii::$app->params['domainRoot'] . '/?email_validation=true');
        }
        return $this->redirect('https://' . Yii::$app->params['domainRoot'] . '/?email_validation=false');
    }

    /**
     * Get a token.
     *
     * @OA\Post(
     *     path="/auth/signin/",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"email": "email@site.com", "password": "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     @OA\Property(
     *                         property="token",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="expired_at",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string"
     *                     ),
     *                     @OA\Property(
     *                         description="Hours",
     *                         property="expires_in",
     *                         type="integer"
     *                     ),
     *                     example={
     *                         "token":
     *     "846d50d91708ae02362e3e19333ddae8aea561c5b83ccb153d79a8e444ddb86850399a67d647a6cdedd946ef05bf68899e5b7002002719b4dc37790c8c11f4a2",
     *                         "expired_at": "2023-12-20 07:47:33",
     *                         "created_at": "2023-12-19 19:47:33",
     *                         "expires_in": 24
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     * @return array|Response
     * @throws BadRequestHttpException
     * @throws UnauthorizedHttpException
     */
    public function actionSignin()
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
            throw new UnauthorizedHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Requests password reset.
     *
     * @OA\Post(
     *     path="/auth/request-password-reset/",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 example={"email": "email@site.com"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "Check your email for further instructions."
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionRequestPasswordReset(): array
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                return $this->successResponse(
                    'Check your email for further instructions.'
                );
            }

            throw new BadRequestHttpException(
                'Sorry, we are unable to reset password for the provided email address.'
            );
        }

        if ($errors = $model->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Resets password.
     *
     * @OA\Post(
     *     path="/auth/reset-password/",
     *     tags={"Auth"},
     *     @OA\Parameter(
     *          name="token",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          style="form"
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"password": "VVpp!!11"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="The data",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(
     *                     example={
     *                         "name": "Success",
     *                         "status": 200,
     *                         "code": 0,
     *                         "message": "New password saved."
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            return $this->successResponse(
                'New password saved.'
            );
        }

        if ($errors = $model->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }
}
