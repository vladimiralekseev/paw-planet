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
     *     tags={"Auth"},
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
     *     tags={"Auth"},
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
     * @OA\Post(
     *     path="/auth/signin/",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"username": "username", "password": "password"}
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
     *                     example={
     *                         "token":
     *     "846d50d91708ae02362e3e19333ddae8aea561c5b83ccb153d79a8e444ddb86850399a67d647a6cdedd946ef05bf68899e5b7002002719b4dc37790c8c11f4a2",
     *                         "expired_at": "2023-12-20 07:47:33",
     *                         "created_at": "2023-12-19 19:47:33"
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
}
