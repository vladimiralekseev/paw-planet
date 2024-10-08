<?php

namespace api\controllers;

use api\models\forms\SubscriptionCancelForm;
use api\models\forms\SubscriptionCheckoutForm;
use common\models\SiteUser;
use Yii;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;

class SubscriptionController extends AccessController
{
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class'   => VerbFilter::class,
                    'actions' => [
                        'checkout' => ['post'],
                        'cancel'   => ['post'],
                    ],
                ],
            ]
        );
    }

    /**
     * Subscription checkout
     *
     * @OA\Post(
     *     path="/subscription/checkout/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Subscription"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"product_id"},
     *                 @OA\Property(
     *                     property="product_id",
     *                     type="integer",
     *                 ),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="See https://docs.stripe.com/api/checkout/sessions/object",
     *         @OA\Link(
     *          link="https://docs.stripe.com/api/checkout/sessions/object"
     *         )
     *     )
     * )
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCheckout(): array
    {
        /** @var SiteUser $user */
        $user = Yii::$app->user->identity;
        $user->refresh();

        $subscriptionCheckoutForm = new SubscriptionCheckoutForm();
        $subscriptionCheckoutForm->load(Yii::$app->request->post());
        $subscriptionCheckoutForm->user_id = $user->id;
        if ($subscriptionCheckoutForm->generate()) {
            return $this->successResponse(
                'Request has created!',
                $subscriptionCheckoutForm->getResponse()
            );
        }
        if ($errors = $subscriptionCheckoutForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }

    /**
     * Subscription cancel
     *
     * @OA\Post(
     *     path="/subscription/cancel/",
     *     security={{"bearerAuth":{}}},
     *     tags={"Subscription"},
     *     @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          in="header",
     *          name="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT",
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="See https://docs.stripe.com/api/subscriptions/cancel",
     *         @OA\Link(
     *          link="https://docs.stripe.com/api/subscriptions/cancel"
     *         )
     *     )
     * )
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCancel(): array
    {
        /** @var SiteUser $user */
        $user = Yii::$app->user->identity;
        $user->refresh();

        $subscriptionCheckoutForm = new SubscriptionCancelForm();
        $subscriptionCheckoutForm->load(Yii::$app->request->post());
        $subscriptionCheckoutForm->user_id = $user->id;
        if ($subscriptionCheckoutForm->cancelSubscription()) {
            return $this->successResponse(
                'Request has created!',
                $subscriptionCheckoutForm->getResponse()
            );
        }
        if ($errors = $subscriptionCheckoutForm->getErrorSummary(true)) {
            throw new BadRequestHttpException(array_shift($errors));
        }

        throw new BadRequestHttpException('Undefined error');
    }
}
