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
                        'login' => ['post'],
                        'verify-email' => ['get'],
                    ],
                ],
            ]
        );
    }

    /**
     * @OA\Post(
     *     path="/auth/signup/",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
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
     *                 example={"username": "username", "email": "email@email.com", "password": "987asdfi7i87"}
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
     * @OA\Get(
     *     path="/auth/verify-email/",
     *     @OA\Parameter(
     *          name="token",
     *          in="query",
     *          @OA\Schema(
     *              type="string",
     *          ),
     *          style="form"
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
     *                         "message": "Your email has been confirmed!"
     *                     }
     *                 )
     *             )
     *         }
     *     )
     * )
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
